<?php
/**
 * @author: Cristian Pana
 * Date: 16.11.2020
 */

namespace CPANA\App\Utils;


interface FileCachingManagerInterface
{
    public function downloadFromUrl(string $url, int $recordId): ?string;
    public function getCachedImageUrl(string $url, int $recordId): ?string;
    public function getErrorMessage(): ?string;
}