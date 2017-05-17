<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\ApiController;

$ankiServerUri = 'http://10.0.2.15:3000';
$apiController = new ApiController($ankiServerUri);

var_dump($apiController->listCollections());
