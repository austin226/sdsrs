<?php

namespace Aalmond\Sdsrs;

class JsonPrinter
{
    public function sendAsJson($data, int $httpStatusCode = 200) : void
    {
        header('Content-Type: application/json');
        http_response_code($httpStatusCode);
        echo json_encode($data);
    }
}
