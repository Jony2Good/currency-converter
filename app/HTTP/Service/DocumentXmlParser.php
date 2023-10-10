<?php

namespace App\HTTP\Service;

use App\System\Interface\DocumentParserInterface;

class DocumentXmlParser implements DocumentParserInterface
{
    use ConverterTrait;

    /**
     * @var array<string>
     */
    protected array $storage = [];
    /**
     * @var array<string>
     */
    protected array $storageFrom = [];

    protected \SimpleXMLElement|false $xmLData;

    /**
     * @param string $data
     * @return \SimpleXMLElement|false
     */
    public function checkData(string $data): \SimpleXMLElement|false
    {
        $this->xmLData = simplexml_load_string($data);
        return $this->xmLData;
    }


    public function readData(string $charCode)
    {
        $storage = [];
        foreach ($this->xmLData as $el) {
          if (strval($el->CharCode) == $charCode) {
               $storage[] = [strval($el->Value), strval($el->Nominal)];
            }
        }
        return $storage;
    }

    public function convert(string $from, string $to, string $amount)
    {
        $exchangeFrom = $this->readData($from);
        $exchangeTo = $this->readData($to);

        if (empty($exchangeFrom) || empty($exchangeTo)) {
            return 'Data is empty';
        }

        $myResult = ((float)$exchangeFrom[0][0] / (float)$exchangeFrom[0][1]) * (float)$amount;
        $totalCash = $myResult / ($exchangeTo[0][0] / $exchangeTo[0][1]);

        return json_encode(array('result' => $totalCash . ' ' . $to));

    }
}
