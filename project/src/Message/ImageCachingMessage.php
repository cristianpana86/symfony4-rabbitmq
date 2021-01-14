<?php
/**
 * @author: Cristian Pana
 * Date: 15.11.2020
 */

namespace CPANA\App\Message;


class ImageCachingMessage
{
    protected $imageId;

    protected $imageUrl;

    public function __construct(int $imageId, string $imageUrl)
    {
        $this->imageId = $imageId;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return int
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }



}