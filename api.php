<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\AnkiApiController;
use Austin226\Sdsrs\JsonPrinter;

$ankiServerUri = 'http://10.0.2.15:3000';
$ankiController = new AnkiApiController($ankiServerUri);
$jsonPrinter = new JsonPrinter();

$collectionList = $ankiController->listCollections();
$jsonPrinter->sendAsJson($collectionList);
