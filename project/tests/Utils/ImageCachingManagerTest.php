<?php
/**
 * @author: Cristian Pana
 * Date: 18.11.2020
 */

namespace CPANA\App\Tests\Utils;

use CPANA\App\Utils\CurlWrapper;
use CPANA\App\Utils\FilesystemAbstraction;
use CPANA\App\Utils\ImageCachingManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ImageCachingManagerTest extends TestCase
{
    public const CACHE_DIR      = 'cache';
    public const PUBLIC_DIR     = '/var/www/project/public';
    public const RECORD_ID      = 1;
    public const IMAGE_URL      = 'https://mgtechtest.blob.core.windows.net/images/unscaled/2012/04/04/funny-games-1997-1S-KA-to-KP4.jpg';

    /* @var $imageCachingManager ImageCachingManager  */
    protected $imageCachingManager;

    public function setUp(): void
    {
        // Create a valid  ImageCachingManager object to be used in several tests
        $logger = $this->createMock(LoggerInterface::class);
        $curlWrapper = $this->createMock(CurlWrapper::class);
        $curlWrapper->method('download')
                    ->willReturn("some_valid_data");

        $filesystem = $this->createMock(FilesystemAbstraction::class);
        $filesystem->method('saveToDisk')
                    ->willReturn(true);
        $imageCachingManager = new ImageCachingManager(self::CACHE_DIR, self::PUBLIC_DIR, $logger, $curlWrapper, $filesystem);
        $this->imageCachingManager = $imageCachingManager;
    }

    /**
     * Test generating absolute local path where the file will saved
     */
    public function testGenerateLocalFilePath()
    {
        $localPath = $this->imageCachingManager->generateLocalFilePath(self::IMAGE_URL, self::RECORD_ID);

        $this->assertEquals('/var/www/project/public/cache/1/funny-games-1997-1S-KA-to-KP4.jpg', $localPath);
    }

    /**
     * Test generating absolute local path where the file will saved
     */
    public function testGetCachedImageUrl()
    {
        $localPath = $this->imageCachingManager->getCachedImageUrl(self::IMAGE_URL, self::RECORD_ID);

        $this->assertEquals('/cache/1/funny-games-1997-1S-KA-to-KP4.jpg', $localPath);
    }

    /**
     * A successfully downloaded file should result in returning the local path where the file is saved
     */
    public function testSuccessDownloadFromUrl()
    {
        $localPath = $this->imageCachingManager->downloadFromUrl(self::IMAGE_URL, self::RECORD_ID);

        // Correct path should be returned
        $this->assertEquals('/var/www/project/public/cache/1/funny-games-1997-1S-KA-to-KP4.jpg', $localPath);
        // Error property should be empty
        $this->assertEquals(true, empty($this->imageCachingManager->getErrorMessage()));
    }

    /**
     * Test what happens when download fails and an exception is thrown
     */
    public function testFailDownloadFromUrl()
    {
        // Create an  ImageCachingManager object which will fail
        $logger = $this->createMock(LoggerInterface::class);
        // simulate cURL failing to download file
        $curlWrapper = $this->createMock(CurlWrapper::class);
        $curlWrapper->method('download')
            ->willThrowException(new \Exception('HTTP 404'));
        $filesystem = $this->createMock(FilesystemAbstraction::class);
        $filesystem->method('saveToDisk')
            ->willReturn(true);
        $failingImageCachingManager = new ImageCachingManager(self::CACHE_DIR, self::PUBLIC_DIR, $logger, $curlWrapper, $filesystem);

        $localPath = $failingImageCachingManager->downloadFromUrl(self::IMAGE_URL, self::RECORD_ID);

        // Function downloadFromUrl() should return false
        $this->assertEquals(false, $localPath);
        // Error should not be empty!
        $this->assertEquals(true, !empty($failingImageCachingManager->getErrorMessage()));
    }

}