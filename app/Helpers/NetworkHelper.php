<?php

namespace App\Helpers;

class NetworkHelper
{
    /**
     * Ping une URL et vérifie si elle est accessible
     *
     * @param string $url
     * @return bool
     */
    public static function pingUrl(string $url): bool
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout après 5 secondes

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $hasError = curl_errno($ch);

        curl_close($ch);

        return !$hasError && $httpCode === 200;
    }
}
