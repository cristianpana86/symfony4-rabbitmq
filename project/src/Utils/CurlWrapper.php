<?php
/**
 * @author: Cristian Pana
 * Date: 18.11.2020
 */

namespace CPANA\App\Utils;

/**
 * Moving cURL call in this class makes it easier to write unit tests without actually calling cURL
 * Class CurlWrapper
 * @package CPANA\App\Utils
 */
class CurlWrapper
{
    public function download(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $httpCode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));

        if ($data === false) {
            throw new \Exception("HTTP status code: ".$httpCode." Error:".curl_error($ch));
        } elseif ($httpCode > 399) {
            throw new \Exception("HTTP status code: ".$httpCode." Error:".$data);
        }

        curl_close($ch);

        return $data;
    }
}