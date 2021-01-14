<?php
/**
 * @author: Cristian Pana
 * Date: 18.11.2020
 */

namespace CPANA\App\Tests\Utils;

use CPANA\App\Utils\InputDataParser;
use PHPUnit\Framework\TestCase;

class InputDataParserTest extends TestCase
{
    /**
     *  Tests if Exception is thrown for invalid file path
     */
    public function testForExceptionReadFile()
    {
        $inputParser = new InputDataParser('some_invalid_url');
        // Expect Exception
        $this->expectException(\Exception::class);
        $inputParser->readFile();
    }

    /**
     * Tests that file content is correctly read from a valid path
     */
    public function testLocalFileReadFile()
    {
        $testFilePath = realpath(__DIR__ .'/../assets/showcase.json');
        // Read md5 file on disk
        $md5 = md5_file($testFilePath);
        $inputParser = new InputDataParser($testFilePath);
        $inputParser->readFile();
        $content = $inputParser->getJsonString();

        // Compare md5 file on disk with the one available with getJsonString() function
        $this->assertEquals($md5, md5($content));
    }

    /**
     * Test that json_decode is successfully executed and an array with 45 records is returned
     */
    public function testGetData()
    {
        $testFilePath = realpath(__DIR__ .'/../assets/showcase.json');
        $inputParser = new InputDataParser($testFilePath);
        $arrayContent = $inputParser->getData();
        // The test file contains 45 records
        $this->assertEquals(45, count($arrayContent));
    }
}