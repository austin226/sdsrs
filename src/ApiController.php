<?php

namespace Austin226\Sdsrs;

use GuzzleHttp\Client;

class ApiController
{
    public function __construct($ankiServerUri)
    {
        $this->ankiServerClient = new Client([
            'base_uri' => $ankiServerUri
        ]);
    }

    public function listCollections()
    {

    }
}
