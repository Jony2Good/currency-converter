<?php

namespace App\HTTP\Service;

use App\System\Interface\DocumentParserInterface;

class DocumentXmlParser implements DocumentParserInterface
{
    /**
     * @var array<string>
     */
    public array $storage = [];

    /**
     * @param mixed $file
     * @return \SimpleXMLElement|false
     */
    public function getData(mixed $file): \SimpleXMLElement|false
    {
        return simplexml_load_string($file);
    }

    /**
     * @param mixed $file
     * @param string $innerCharCode
     * @return array<string>
     */
    public function readData(mixed $file, string $innerCharCode): array
    {
        $data = $this->getData($file);

        foreach ($data as $el) {
            if (strval($el->CharCode) === $innerCharCode) {
                return $this->storage[] = [strval($el->Value), strval($el->Nominal)];
            }
        }
        return $this->storage;
    }
}
