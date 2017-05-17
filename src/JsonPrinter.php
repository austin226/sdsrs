<?php

namespace Austin226\Sdsrs;

class JsonPrinter
{
    public function sendAsJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
