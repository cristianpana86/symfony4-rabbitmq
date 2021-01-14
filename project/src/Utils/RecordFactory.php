<?php
/**
 * @author: Cristian Pana
 * Date: 14.11.2020
 */

namespace CPANA\App\Utils;

use CPANA\App\Entity\Image;
use CPANA\App\Entity\Record;

/**
 * Class RecordBuilder
 * Creates a Record object and associated Image objects from raw array data
 * @package CPANA\App\Utils
 */
class RecordFactory
{

    public static function createFromRawData(array $data)
    {
        $record = new Record();
        $i=1;
        if(isset($data['body'])) {
            $record->setBody($data['body']);
        }
        if(isset($data['cardImages']) && is_array($data['cardImages'])) {
            foreach ($data['cardImages'] as $dataImage) {
                ${"image" . $i} = new Image();
                ${"image" . $i}->setUrl($dataImage['url']);
                ${"image" . $i}->setHeight($dataImage['h']);
                ${"image" . $i}->setWidth($dataImage['w']);
                ${"image" . $i}->setType(Image::TYPE_CARD);
                ${"image" . $i}->setRecord($record);
                $record->addImage(${"image" . $i});
            }
        }
        if(isset($data['cast'])) {
            $record->setCast($data['cast']);
        }
        if(isset($data['cert'])) {
            $record->setCert($data['cert']);
        }
        if(isset($data['directors'])) {
            $record->setDirectors($data['directors']);
        }
        if(isset($data['duration'])) {
            $record->setDuration((int)$data['duration']);
        }
        if(isset($data['genres'])) {
            $record->setGenres($data['genres']);
        }
        if(isset($data['headline'])) {
            $record->setHeadline($data['headline']);
        }
        if(isset($data['id'])) {
            $record->setExternalId($data['id']);
        }
        if(isset($data['keyArtImages']) && is_array($data['keyArtImages'])) {
            foreach ($data['keyArtImages'] as $dataImage) {
                ${"image" . $i} = new Image();
                ${"image" . $i}->setUrl($dataImage['url']);
                ${"image" . $i}->setHeight($dataImage['h']);
                ${"image" . $i}->setWidth($dataImage['w']);
                ${"image" . $i}->setType(Image::TYPE_KEY_ART);
                ${"image" . $i}->setRecord($record);
                $record->addImage(${"image" . $i});
            }
        }
        if(isset($data['quote'])) {
            $record->setQuote($data['quote']);
        }
        if(isset($data['rating'])) {
            $record->setRating((int)$data['rating']);
        }
        if(isset($data['reviewAuthor'])) {
            $record->setReviewAuthor($data['reviewAuthor']);
        }
        if(isset($data['skyGoId'])) {
            $record->setSkyGoId($data['skyGoId']);
        }
        if(isset($data['skyGoUrl'])) {
            $record->setSkyGoUrl($data['skyGoUrl']);
        }
        if(isset($data['sum'])) {
            $record->setSum($data['sum']);
        }
        if(isset($data['synopsis'])) {
            $record->setSynopsis($data['synopsis']);
        }
        if(isset($data['url'])) {
            $record->setUrl($data['url']);
        }

        return $record;
    }
}