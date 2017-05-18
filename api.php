<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\ApiController;
use Austin226\Sdsrs\Exceptions\HttpException;
use Austin226\Sdsrs\JsonPrinter;

$ankiServerUri = 'http://10.0.2.15:3000';
$apiController = new ApiController($ankiServerUri);
$jsonPrinter = new JsonPrinter();

try {
    $response = $apiController->doAction($_GET);
} catch (HttpException $e) {
    $jsonPrinter->sendAsJson($e->getMessage(), $e->getCode());
    exit();
}

$jsonPrinter->sendAsJson($response);
