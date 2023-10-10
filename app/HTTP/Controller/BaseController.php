<?php

namespace App\HTTP\Controller;

use App\HTTP\Service\DocumentJsonParser;
use App\HTTP\Service\DocumentXmlParser;
use App\HTTP\Service\ParseData;

class BaseController
{
    public function __construct(
        protected DocumentXmlParser  $xmlData = new DocumentXmlParser(),
        protected DocumentJsonParser $jsonData = new DocumentJsonParser()
    )
    {

    }

    /**
     * @return string|null
     */
    public function read(): string|null
    {
        $country = $_POST['country'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $from = strtoupper($_POST['from']) ?? '';
        $to = strtoupper($_POST['to']) ?? '';


        $data = ParseData::read($country);

        if ($this->xmlData->checkData($data)) {
            return $this->xmlData->convert($from, $to, $amount);
        }

        return json_encode(array('message' => "Error with data"));
    }



}
