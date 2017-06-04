<?php

require 'vendor/autoload.php';

use Aalmond\Sdsrs\Anki\AnkiApiController;
use Aalmond\Sdsrs\Api\ApiController;
use Aalmond\Sdsrs\Exceptions\BadRequestException;
use Aalmond\Sdsrs\Exceptions\HttpException;
use Aalmond\Sdsrs\Exceptions\MethodNotAllowedException;
use Aalmond\Sdsrs\JsonPrinter;
use Aalmond\Sdsrs\Logging\FileAppendingLogger;

$config = json_decode(file_get_contents('api_config.json'), true);
$ankiServerUri = $config['uri'];
$ankiApiController = new AnkiApiController($ankiServerUri);
$logger = new FileAppendingLogger(__DIR__.'/log/debug.log');
$apiController = new ApiController($ankiApiController);
$apiController->setLogger($logger);
$jsonPrinter = new JsonPrinter();

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $requestBody = json_decode(file_get_contents('php://input'), true) or die("Could not decode json");
            $response = $apiController->handleRequest($requestBody);
            break;
        default:
            throw new MethodNotAllowedException("Method not allowed: ".$_SERVER['REQUEST_METHOD']);
    }
} catch (HttpException $e) {
    $logger->error("Received exception: ".$e->getMessage(), ['code' => $e->getCode()]);
    //$jsonPrinter->sendAsJson($e->getMessage(), $e->getCode());
    exit();
}

$jsonPrinter->sendAsJson($response);
