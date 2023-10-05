<?php

namespace App\HTTP\Service;

class ParseData
{
    /**
     * @param string $country
     * @return bool|string
     */
    public static function read(string $country): bool|string
    {
        switch ($country) {
            case CountryCentralBank::Russia->name:
                return self::parseLink(CountryCentralBank::Russia->getCountryCentralBank());
            case CountryCentralBank::Thailand->name:
                return self::parseLink(CountryCentralBank::Thailand->getCountryCentralBank(), 'X-IBM-Client-Id: c2bbe063-d0ff-456c-bc08-fbd5115fb340');
        }
        return json_encode(array('message' => "Error with parse link"));
    }

    /**
     * @param string $link
     * @param bool|string $api_key
     * @return bool|string
     */
    private static function parseLink(string $link, bool|string $api_key = false): bool|string
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$api_key, "apikey: lxhlDcBhVBdQf4Yc0O0LsKbZq9uxLyk6"]);
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

    /**
     * @param string $to
     * @param string $from
     * @param string $amount
     * @return bool|string
     */
    public static function parseDefault(string $to, string $from, string $amount): bool|string
    {
        $link = 'https://api.apilayer.com/exchangerates_data/convert?to=' . $to . '&from=' . $from . '&amount=' . $amount;
        return ParseData::parseLink($link);
    }

}
