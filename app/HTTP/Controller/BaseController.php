<?php

namespace App\HTTP\Controller;

use App\HTTP\Service\DocumentJsonParser;
use App\HTTP\Service\DocumentXmlParser;
use App\HTTP\Service\ParseData;

class BaseController
{
    public function __construct(
        protected DocumentXmlParser $xmlData = new DocumentXmlParser(),
        protected DocumentJsonParser $jsonData = new DocumentJsonParser()
    ) {

    }

    /**
     * @return string
     */
    public function read(): string
    {
        $country = $_POST['country'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $from = strtoupper($_POST['from']) ?? '';
        $to = strtoupper($_POST['to']) ?? '';

        if ($_POST['from'] === $_POST['to']) {
            echo json_encode(array('message' => "Error with exchange amount"));
            http_response_code(404);
            die();
        }

        if(empty($country)) {
            $defaultData = json_decode(ParseData::parseDefault($to, $from, $amount), true);
            return json_encode(array('result' => $defaultData['result'] . ' '. $defaultData['query']['to']));
        }

        $data = ParseData::read($country);

        if ($this->xmlData->getData($data)) {
            return $this->convert($data, $this->xmlData, $from, $to, $amount);
        }

        if ($this->jsonData->getData($data)) {
            return $this->convert($data, $this->jsonData, $from, $to, $amount);
        }
        return json_encode(array('message' => "Error with data"));
    }

    protected function convert(mixed $data, object $obj, string $from, string $to, string $amount): false|string
    {
        $exchangeFrom = $obj->readData($data, $from);
        $exchangeTo = $obj->readData($data, $to);

        $myResult = (int)$amount * ($exchangeFrom[0] / $exchangeFrom[1]);
        $totalCash = round($myResult / ($exchangeTo[0] / $exchangeTo[1]), 4);

        return json_encode(array('result' => $totalCash . ' ' . $to));
    }
}
