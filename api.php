<?php

require 'vendor/autoload.php';

use Aalmond\Sdsrs\Anki\AnkiApiController;
use Aalmond\Sdsrs\Api\ApiController;
use Aalmond\Sdsrs\Exceptions\BadRequestException;
use Aalmond\Sdsrs\Exceptions\HttpException;
use Aalmond\Sdsrs\Exceptions\MethodNotAllowedException;
use Aalmond\Sdsrs\JsonPrinter;

$config = json_decode(file_get_contents('api_config.json'), true);
$ankiServerUri = $config['uri'];
$ankiApiController = new AnkiApiController($ankiServerUri);
$apiController = new ApiController($ankiApiController);
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
            $requestBody = json_decode(file_get_contents('php://input'), true) or die("Could not decode json");
            $response = $apiController->doAction('POST', $action, $requestBody);
            break;
        default:
            throw new MethodNotAllowedException("Method not allowed: ".$_SERVER['REQUEST_METHOD']);
    }
} catch (HttpException $e) {
    $jsonPrinter->sendAsJson($e->getMessage(), $e->getCode());
    exit();
}

$jsonPrinter->sendAsJson($response);
