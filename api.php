<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\ApiController;
use Austin226\Sdsrs\Exceptions\HttpException;
use Austin226\Sdsrs\Exceptions\MethodNotAllowedException;
use Austin226\Sdsrs\JsonPrinter;

$ankiServerUri = 'http://10.0.2.15:3000';
$apiController = new ApiController($ankiServerUri);
$jsonPrinter = new JsonPrinter();

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $response = $apiController->doAction('GET', $_GET);
            break;
        case 'POST':
            $response = $apiController->doAction('POST', $_POST);
            break;
        default:
            throw new MethodNotAllowedException("Method not allowed: ".$_SERVER['REQUEST_METHOD']);
    }
} catch (HttpException $e) {
    $jsonPrinter->sendAsJson($e->getMessage(), $e->getCode());
    exit();
}

$jsonPrinter->sendAsJson($response);
