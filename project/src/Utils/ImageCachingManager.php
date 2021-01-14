<?php
/**
 * @author: Cristian Pana
 * Date: 16.11.2020
 */

namespace CPANA\App\Utils;

use CPANA\App\Utils\FileCachingManagerInterface;
use CPANA\App\Utils\CurlWrapper;
use Psr\Log\LoggerInterface;

class ImageCachingManager implements FileCachingManagerInterface
{
    protected $cacheFolder;

    protected $publicDir;

    protected $localFilePath;

    protected $errorMessage;

    protected $logger;

    /* @var $curlWrapper CurlWrapper */
    protected $curlWrapper;

    /* @var $filesystem FilesystemAbstraction  */
    protected $filesystem;

    /**
     * ImageCachingManager constructor.
     * @param string $cacheFolder  - relative to $publicDir (Ex  cache )
     * @param string $publicDir    - absolute path to public directory (Ex  /var/www/project/public  )
     * @param LoggerInterface $logger
     */
    public function __construct(string $cacheFolder, string $publicDir, LoggerInterface $logger, CurlWrapper $curlWrapper, FilesystemAbstraction $filesystem)
    {
        $this->cacheFolder = $cacheFolder;
        $this->publicDir   = $publicDir;
        $this->logger      = $logger;
        $this->curlWrapper = $curlWrapper;
        $this->filesystem = $filesystem;
    }

    /**
     * Generates the absolute path where the file will be saved on the disk
     * Example:  /var/www/project/public/cache/1/funny-games-1997-1S-KA-to-KP4.jpg
     * @param string $url
     * @param int $recordId
     * @return string
     */
    public function generateLocalFilePath(string $url, int $recordId): string
    {
        return $this->publicDir.'/'.$this->cacheFolder.'/'.$recordId.'/'.basename($url);
    }

    /**
     * Generates local path relativ to public directory
     * Example   /cache/1/funny-games-1997-1S-KA-to-KP4.jpg
     * @param string $url
     * @param int $recordId
     * @return string
     */
    public function getCachedImageUrl(string $url, int $recordId): string
    {
        return '/'.$this->cacheFolder.'/'.$recordId.'/'.basename($url);
    }

    public function getErrorMessage(): ?string
    {
        // Comment field is limited to 1000
        return substr($this->errorMessage, 0, 1000);
    }


    /**
     * Download file from URL to a local cache folder
     * Return local file path if success or false if operation failed
     * @param string $url
     * @param int $recordId
     * @return null|string
     */
    public function downloadFromUrl(string $url, int $recordId): ?string
    {
        $this->errorMessage = null; // reset error
        try {
            $localFilePath = $this->generateLocalFilePath($url, $recordId);
            // Move cURL calls to a different class in order to be able to unit test
            $data = $this->curlWrapper->download($url);
            $this->filesystem->saveToDisk($data,$localFilePath);
            return $localFilePath;

        } catch (\Throwable $e) {
            // Log error and move on
            echo "An error occured while trying to download from URL: {$url}.".PHP_EOL;
            $this->errorMessage = $e->getMessage();
            $this->logger->error($this->errorMessage . " Error:". $e->getMessage());
            return false;
        }
    }

}