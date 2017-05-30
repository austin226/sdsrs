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

    public function listCollections() : array
    {
        $response = $this->ankiServerClient->post('list_collections');
        $responseBody = $response->getBody();
        $collectionList = json_decode($responseBody, true);
        return $collectionList;
    }

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

    public function addCard(string $collectionName, string $front, string $back) : int
    {
        // TODO
    }

    private function findAllNotes(string $collectionName) : array
    {
        $url = "collection/";
        // TODO
    }

    public function nextCard(string $collectionName, string $deckName) : array
    {
        $url = "collection/{$collectionName}/next_card";
        $requestData = ['deck' => $deckName];
        $response = $this->ankiServerClient->post($url, ['json' => $requestData]);

        var_dump($response->getBody());
        // TODO
    }

    public function resetScheduler(string $collectionName, string $deckName)
    {
        $url = "collection/{$collectionName}/reset_scheduler";
        $requestData = ['deck' => $deckName];
        $response = $this->ankiServerClient->post($url, ['json' => $requestData]);

        var_dump($response->getBody());
        // TODO
    }
}
