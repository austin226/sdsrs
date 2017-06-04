<?php

namespace Aalmond\Sdsrs\Anki;

use Aalmond\Sdsrs\ApiAi\SpeechResponse;
use Aalmond\Sdsrs\Exceptions\HttpException;
use Aalmond\Sdsrs\Exceptions\ResourceNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AnkiApiController implements AnkiApiControllerInterface, LoggerAwareInterface
{
    private $ankiServerClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(string $ankiServerUri)
    {
        $this->ankiServerClient = new Client([
            'base_uri' => $ankiServerUri
        ]);
        $this->setLogger(new NullLogger());
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function getResponseData(string $url, array $requestData = []) : array
    {
        $this->logger->debug("Making request to $url with data ".json_encode($requestData));
        try {
            $response = $this->ankiServerClient->post($url, ['json' => $requestData]);
        } catch (TransferException $e) {
            $this->logger->error("Anki responded with error");
            throw new HttpException($e->getResponse()->getBody()->getContents(), $e->getCode());
        }
        $responseBody = $response->getBody();
        $this->logger->debug("Response body: ".json_encode($responseBody));
        return $responseBody;
    }

    public function listCollections() : SpeechResponse
    {
        $responseBody = $this->getResponseData('list_collections');
        $collectionList = json_decode($responseBody, true);
        $collectionList = array_map('urldecode', $collectionList);

        $outputSpeech = "You have the following collections: ";
        $outputSpeech .= implode(', ', $collectionList);

        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            ['collections' => $collectionList]
        );
        return $speechResponse;
    }

    public function createDeck(string $collectionName, string $deckName, string $count) : SpeechResponse
    {
        $url = "collection/{$collectionName}/create_dynamic_deck";
        $requestData = [
            'name' => $deckName,
            'count' => intval($count),
            'mode' => 'random'
        ];

        $responseBody = $this->getResponseData($url, $requestData);

        $outputSpeech = "Deck created in $collectionName with name $deckName";
        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            ['deckData' => $responseBody]
        );
        return $speechResponse;
    }

    public function listDecks(string $collectionName) : SpeechResponse
    {
        $url = "collection/{$collectionName}/list_decks";
        $response = $this->ankiServerClient->post($url, ['json' => []]);
        $responseBody = $response->getBody();
        $deckDataArray = json_decode($responseBody, true);

        $deckList = [];
        foreach ($deckDataArray as $deckData) {
            $deckList[] = $deckData['name'];
        }

        $outputSpeech = "You have the following decks in $collectionName: ";
        $outputSpeech .= implode(', ', $deckList);

        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            ['collection' => $collectionName, 'decks' => $deckList]
        );
        return $speechResponse;
    }

    public function addCard(string $collectionName, string $front, string $back) : SpeechResponse
    {
        $url = "collection/{$collectionName}/add_note";
        $requestData = [
            'model' => 'Basic',
            'fields' => [
                'Front' => $front,
                'Back' => $back
            ]
        ];
        $response = $this->ankiServerClient->post($url, ['json' => $requestData]);

        $outputSpeech = "Card added to $collectionName";
        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            ['front' => $front, 'back' => $back]
        );
        return $speechResponse;
    }

    private function findAllNotes(string $collectionName) : SpeechResponse
    {
        $url = "collection/";
        // TODO
    }

    private function parseAnswer(string $rawAnswer) : SpeechResponse
    {
        preg_match("/<hr id=answer>(\\n)*(.*)/", $rawAnswer, $matches);
        return $matches[2];
    }

    public function nextCard(string $collectionName, string $deckName) : SpeechResponse
    {
        $url = "collection/{$collectionName}/next_card";
        $requestData = ['deck' => $deckName];
        $response = $this->ankiServerClient->post($url, ['json' => $requestData]);
        $responseBody = $response->getBody();

        $cardDataArray = json_decode($responseBody, true);
        if (empty($cardDataArray)) {
            return [];
        }

        $question = $cardDataArray['question'];

        // Parse answer
        $answer = $this->parseAnswer($cardDataArray['answer']);

        // Parse answer buttons
        $answerButtons = [];
        foreach ($cardDataArray['answer_buttons'] as $answerButton) {
            $answerButtons[] = [
                'ease' => $answerButton['ease'],
                'label' => $answerButton['string_label']
            ];
        }

        return [
            'id' => $cardDataArray['id'],
            'question' => $question,
            'answer' => $answer,
            'answer_buttons' => $answerButtons,
        ];
    }

    public function resetScheduler(string $collectionName, string $deckName) : SpeechResponse
    {
        $url = "collection/{$collectionName}/reset_scheduler";
        $requestData = ['deck' => $deckName];
        $response = $this->ankiServerClient->post($url, ['json' => $requestData]);
        $responseBody = $response->getBody();
        $schedulerDataArray = json_decode($responseBody, true);

        return [
            'cards' => [
                'review' => $schedulerDataArray['review_cards'],
                'learning' => $schedulerDataArray['learning_cards'],
                'new' => $schedulerDataArray['new_cards']
            ]
        ];
    }

    public function answerCard(string $collectionName, string $cardID, string $answer) : SpeechResponse
    {
        $url = "collection/{$collectionName}/answer_card";
        $requestData = [
            'id' => $cardID,
            'ease' => $answer
        ];

        $response = $this->ankiServerClient->post($url, ['json' => $requestData]);
        $responseBody = $response->getBody();
        $responseDataArray = json_decode($responseBody, true);

        return [
            'response' => $responseDataArray
        ];
    }
}
