<?php
/**
 * @author: Cristian Pana
 * Date: 18.11.2020
 */

namespace CPANA\App\Utils;


class FilesystemAbstraction
{
    // TODO: use an actual filesystem abstraction library like https://github.com/thephpleague/flysystem
    /**
     * @param $data
     * @param $localFilePath
     */
    public function saveToDisk($data, $localFilePath)
    {
        $folderPath = dirname($localFilePath);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $file = fopen($localFilePath, "w");
        fputs($file, $data);
        fclose($file);
    }
}