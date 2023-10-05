<?php

namespace App\HTTP\Service;

class ParseData
{
    /**
     * @param string $country
     * @return bool|string|null
     */
    public static function read(string $country): bool|string|null
    {
        switch ($country) {
            case CountryCentralBank::Russia->name:
                return self::parseLink(CountryCentralBank::Russia->getCountryCentralBank());
            case CountryCentralBank::Thailand->name:
                return self::parseLink(CountryCentralBank::Thailand->getCountryCentralBank(), 'X-IBM-Client-Id: c2bbe063-d0ff-456c-bc08-fbd5115fb340');
            default:
                echo 'Wrong data';
        }
        return null;
    }

    /**
     * @param string $link
     * @return bool|string
     */
    private static function parseLink(string $link, bool|string $api_key = false): bool|string
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$api_key]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $result = curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $result;
    }

}