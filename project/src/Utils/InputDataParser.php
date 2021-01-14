<?php
/**
 * @author: Cristian Pana
 * Date: 14.11.2020
 */

namespace CPANA\App\Utils;


class InputDataParser
{
    /** @var  string */
    protected $inputJsonUrl;

    /** @var  string */
    protected $jsonString;


    function __construct(string $inputJsonUrl)
    {
        $this->inputJsonUrl = $inputJsonUrl;
    }

    /**
     * Read content from URL or local path. Save raw content to $this->jsonString
     * @throws \Exception
     */
    function readFile()
    {
        try {
            $content = file_get_contents($this->inputJsonUrl);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        if(false === $content)
            throw new \Exception('Cannot download input JSON from location:' . $this->inputJsonUrl);
        $this->jsonString = $content;
    }


    /**
     * @return string
     */
    public function getInputJsonUrl(): string
    {
        return $this->inputJsonUrl;
    }

    /**
     * @return mixed
     */
    public function getJsonString(): string
    {
        return $this->jsonString;
    }

    /**
     * Read data and decode JSON
     * @return mixed
     */
    public function getData(): array
    {
        $this->readFile();
        // Convert file encoding from  ISO-8859-1 to UTF-8
        $content = utf8_encode($this->jsonString);

        // Make sure json_decode will throw an Exception if encounters any problem
        // Also
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * Decode JSON and also count number of records and number of images found in those records
     * @return array
     */
    public function getDataAndStats(): array
    {
        $rawData = $this->getData();

        $imagesCounter = 0;
        $recordsCounter = count($rawData);
        //  Count how many images to be cached are found in this data set
        foreach ($rawData as $record) {
            if(!empty($record['cardImages'])) {
                foreach ($record['cardImages'] as $img) {
                    $imagesCounter++;
                }
            }
            if(!empty($record['keyArtImages'])) {
                foreach ($record['keyArtImages'] as $img) {
                    $imagesCounter++;
                }
            }
        }
        $result = [
            'records_counter' => $recordsCounter,
            'images_counter'  => $imagesCounter,
            'raw_data'        => $rawData
        ];
        return $result;

    }
}