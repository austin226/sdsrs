<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\AnkiApiController;

$ankiServerUri = 'http://10.0.2.15:3000';
$ankiController = new AnkiApiController($ankiServerUri);

var_dump($ankiController->listCollections());
