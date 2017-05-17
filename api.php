<?php

require 'vendor/autoload.php';

use Austin226\Sdsrs\ApiController;

$ankiServerUri = 'http://localhost:3000';
$apiController = new ApiController($ankiServerUri);

var_dump($apiController);
