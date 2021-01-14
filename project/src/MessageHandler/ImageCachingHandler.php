<?php
/**
 * @author: Cristian Pana
 * Date: 15.11.2020
 */

namespace CPANA\App\MessageHandler;

use CPANA\App\Entity\CachingLog;
use CPANA\App\Entity\Image;
use CPANA\App\Message\ImageCachingMessage;
use CPANA\App\Utils\FileCachingManagerInterface;
use CPANA\App\Utils\ImageCachingManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ImageCachingHandler implements MessageHandlerInterface
{
    protected $em;

    /* @var $cachingManager FileCachingManagerInterface */
    protected $cachingManager;
    /* @var $doctrine Registry */
    protected $doctrine;
    /* @var $logger LoggerInterface */
    protected  $logger;

    public function __construct(EntityManagerInterface $em, FileCachingManagerInterface $cachingManager, ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->em             = $em;
        $this->cachingManager = $cachingManager;
        $this->doctrine       = $doctrine;
        $this->logger         = $logger;
    }

    public function __invoke(ImageCachingMessage $imageCachingMessage)
    {
        $imageId = $imageCachingMessage->getImageId();

        /* @var $image \CPANA\App\Entity\Image */
        $image = $this->em->getRepository(Image::class)->find($imageId);

        // Print current processed URL
        echo "Current image:" . $image->getUrl() . PHP_EOL;

        // Organize cached images by related record (movie) id
        $recordId = $image->getRecord()->getId();
        $localFilePath  = $this->cachingManager->downloadFromUrl($image->getUrl(), $recordId);
        $cachedImageUrl = $this->cachingManager->getCachedImageUrl($image->getUrl(), $recordId);
        $errorMessage   = $this->cachingManager->getErrorMessage();

        // Set status
        if(empty($localFilePath)) {
            $imageStatus = Image::STATUS_CACHING_FAILED;
        } else {
            $imageStatus = Image::STATUS_CACHING_SUCCESS;
        }

        // For batch inserts disable SQL logger
        // https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/batch-processing.html
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        try {
            // Get info about which consumer executing this task
            $host = gethostname();
            $ip = gethostbyname($host);

            // Update image and persist new CachingLog
            if ($image instanceof Image) {
                $image->setStatus($imageStatus);
                $image->setComment($errorMessage);
                $log = new CachingLog();
                $log->setImage($image);
                $log->setUrl($image->getUrl());
                $log->setStatus($imageStatus);
                $log->setComment($errorMessage);
                $log->setConsumerInfo($host . ' ' . $ip);
                if (!empty($localFilePath)) {
                    $image->setUrlCache($cachedImageUrl);
                    $log->setUrlCache($cachedImageUrl);
                }
                $this->em->persist($image);
                $this->em->persist($log);

            }

            $this->em->flush(); //Persist objects that did not make up an entire batch
            $this->em->clear();

        } catch (\Throwable $e) {
            // Check if em is not closed after error
            if(!$this->em->isOpen())
            {
                // https://www.iditect.com/how-to/52164098.html
                $this->doctrine->resetManager();
                $this->em = $this->doctrine->getManager();
            }
            // Display info
            echo "Error" . $e->getMessage() . PHP_EOL;
            // Log error
            $this->logger->error($e->getMessage());
        }
    }
}