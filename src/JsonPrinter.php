<?php

namespace Austin226\Sdsrs;

class JsonPrinter
{
    public function sendAsJson($data, $httpStatusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($httpStatusCode);
        echo json_encode($data);
    }
}
