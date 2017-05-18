<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\ApiController;
use Austin226\Sdsrs\AnkiApiController;
use Austin226\Sdsrs\JsonPrinter;

$ankiServerUri = 'http://10.0.2.15:3000';
$apiController = new ApiController($ankiServerUri);
$jsonPrinter = new JsonPrinter();

if (empty($_GET['action'])) {
    $jsonPrinter->sendAsJson('No action specified', 400);
    exit();
}

$jsonPrinter->sendAsJson($collectionList);
