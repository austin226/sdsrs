<?php

namespace Aalmond\Sdsrs\Anki;

use Aalmond\Sdsrs\Exceptions\ResourceNotFoundException;
use GuzzleHttp\Client;

class AnkiApiController implements AnkiApiControllerInterface
{
    private $ankiServerClient;

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
     * Lists all decks in a collection. Throws a ResourceNotFoundException
     * if collection is not found.
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\ResourceNotFoundException
     */
    public function listDecks(string $collectionName) : array
    {
        $url = "collection/{$collectionName}/list_decks";
        $response = $this->ankiServerClient->post($url, ['json' => []]);
        $responseBody = $response->getBody();
        $deckDataArray = json_decode($responseBody, true);

        $deckList = [];
        foreach ($deckDataArray as $deckData) {
            $deckList[] = [
                'id' => $deckData['id'],
                'name' => $deckData['name']
            ];
        }
        return $deckList;
    }
}
