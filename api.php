<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\ApiController;
use Austin226\Sdsrs\Exceptions\BadRequestException;
use Austin226\Sdsrs\Exceptions\HttpException;
use Austin226\Sdsrs\Exceptions\MethodNotAllowedException;
use Austin226\Sdsrs\JsonPrinter;

$ankiServerUri = 'http://10.0.2.15:3000';
$apiController = new ApiController($ankiServerUri);
$jsonPrinter = new JsonPrinter();

try {
    if (!isset($_REQUEST['action'])) {
        throw new BadRequestException('You must specify an action.');
    }

    $action = $_REQUEST['action'];

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $response = $apiController->doAction('GET', $action, $_GET);
            break;
        case 'POST':
            $response = $apiController->doAction('POST', $action, $_POST);
            break;
        default:
            throw new MethodNotAllowedException("Method not allowed: ".$_SERVER['REQUEST_METHOD']);
    }
} catch (HttpException $e) {
    $jsonPrinter->sendAsJson($e->getMessage(), $e->getCode());
    exit();
}

$jsonPrinter->sendAsJson($response);
