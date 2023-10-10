<?php

namespace App\HTTP\Service;

use App\System\Interface\DocumentParserInterface;

class DocumentJsonParser implements DocumentParserInterface
{
    /**
     * @var array<string>
     */
    public array $storage = [];

    /**
     * @param mixed $file
     * @return mixed
     */
    public function getData(mixed $file): mixed
    {
        return json_decode($file, true);
    }

    /**
     * @param string $innerCharCode
     * @return array<string>
     */
    public function readData(string $innerCharCode): array
    {
//        $data = $this->getData($file);
//
//        foreach ($data['result']['data']['data_detail'] as $el) {
//            $num = preg_replace('/[^0-9]/', '', $el['currency_name_eng']);
//
//            if ($el['currency_id'] === $innerCharCode) {
//                return $this->storage[] = [$el['selling'], empty($num) ? '1' : $num];
//            }
//        }
        return $this->storage;
    }
}
