<?php

namespace Austin226\Sdsrs;

use Austin226\Sdsrs\Exceptions\ResourceNotFoundException;
use GuzzleHttp\Client;

class AnkiApiController
{
    public function __construct(string $ankiServerUri)
    {
        $this->ankiServerClient = new Client([
            'base_uri' => $ankiServerUri
        ]);
    }

    /**
     * Lists all collections we know of.
     *
     * @return array
     */
    public function listCollections() : array
    {
        $response = $this->ankiServerClient->post('list_collections');
        $responseBody = $response->getBody();
        $collectionList = json_decode($responseBody, true);
        return $collectionList;
    }

    /**
     * Sets the internal collection name.
     *
     * @param string $collection
     *
     * @throws \Austin226\Sdsrs\Exceptions\ResourceNotFoundException if collection not found
     */
    public function setCollection(string $collection) : void
    {
        $validCollections = $this->listCollections();
        if (!in_array($collection, $validCollections)) {
            throw new ResourceNotFoundException("Not a valid collection: '$collection'");
        }
    }
}
