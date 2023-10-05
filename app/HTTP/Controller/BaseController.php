<?php

namespace App\HTTP\Controller;

use App\HTTP\Service\DocumentJsonParser;
use App\HTTP\Service\DocumentXmlParser;
use App\HTTP\Service\ParseData;
use App\HTTP\Service\ServiceContainer;


class BaseController extends ServiceContainer
{
    public function __construct(protected $xmlData = new DocumentXmlParser(),
                                protected $jsonData = new DocumentJsonParser())
    {

    }

    public function index(string $country): bool|string|null
    {
       return ParseData::read($country);
    }

    /**
     * @return string
     */
    public function read()
    {
        $country = $_POST['country'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $from = $_POST['from'] ?? '';
        $to = $_POST['to'] ?? '';

        if ($_POST['from'] === $_POST['to']) {
            echo json_encode(array('message' => "Error with exchange amount"));
            http_response_code(404);
            die();
        }

        $data = ParseData::read($country);

        if ($this->xmlData->getData($data)) {
            return $this->convert($data, $this->xmlData, $from, $to, $amount);
        }

        if($this->jsonData->getData($data)) {
            return $this->convert($data, $this->jsonData, $from, $to, $amount);
        }
    }

    protected function convert(mixed $data, $obj, string $from, string $to, string $amount): false|string
    {
        $exchangeFrom = $obj->readData($data, strtoupper($from));
        $exchangeTo = $obj->readData($data, strtoupper($to));

        $myResult = $amount * ($exchangeFrom[0] /  $exchangeFrom[1]);
        $totalCash = round($myResult / ($exchangeTo[0] / $exchangeTo[1]), 4);

        return json_encode(array('result' => $totalCash . ' ' . $to));
    }
}