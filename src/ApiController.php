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
        $response = $this->ankiServerClient->post('list_collections');
        var_dump($response);
        $collectionList = json_decode($response, true);
    }
}
