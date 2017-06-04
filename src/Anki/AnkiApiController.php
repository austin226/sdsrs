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

    private function getResponseData(string $url, ?array $requestData = null) : array
    {
        $this->logger->debug("Making request to $url with data ".json_encode($requestData));
        try {
            if (isset($requestData)) {
                $response = $this->ankiServerClient->post($url, ['json' => $requestData]);
            } else {
                $response = $this->ankiServerClient->post($url);
            }
        } catch (TransferException $e) {
            $this->logger->error("Anki responded with error");
            throw new HttpException($e->getResponse()->getBody()->getContents(), $e->getCode());
        }
        $responseBody = trim($response->getBody());
        $this->logger->debug("Response body: $responseBody");
        if (empty($responseBody)) {
            return [];
        }
        return json_decode($responseBody, true);
    }

    public function listCollections() : SpeechResponse
    {
        $collectionList = $this->getResponseData('list_collections');
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
        $responseData = $this->getResponseData($url, $requestData);

        $outputSpeech = "Card added to $collectionName";
        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            ['front' => $front, 'back' => $back]
        );
        return $speechResponse;
    }

    private function parseAnswer(string $rawAnswer) : string
    {
        preg_match("/<hr id=answer>(\\n)*(.*)/", $rawAnswer, $matches);
        return $matches[2];
    }

    public function nextCard(string $collectionName) : SpeechResponse
    {
        $url = "collection/{$collectionName}/next_card";
        $requestData = ['deck' => 'Default'];
        $cardDataArray = $this->getResponseData($url, $requestData);

        if (empty($cardDataArray)) {
            $outputSpeech = "You are out of cards.";
            $speechResponse = new SpeechResponse(
                $outputSpeech,
                $outputSpeech,
                ['collection' => $collectionName]
            );
            return $speechResponse;
        }

        $question = $cardDataArray['question'];
        $this->logger->debug("Parsed question: $question");

        // Parse answer
        $answer = $this->parseAnswer($cardDataArray['answer']);
        $this->logger->debug("Parsed answer: $answer");

        // Parse answer buttons
        $answerButtons = [];
        foreach ($cardDataArray['answer_buttons'] as $answerButton) {
            $answerButtons[] = [
                'ease' => $answerButton['ease'],
                'label' => $answerButton['string_label']
            ];
        }
        $this->logger->debug("Parsed answer buttons: ".json_encode($answerButtons));

        $cardDataOutput = [
            'id' => $cardDataArray['id'],
            'question' => $question,
            'answer' => $answer,
            'answer_buttons' => $answerButtons,
        ];

        $contextOut = [
            'name' => 'cardData',
            'lifespan' => 2,
            'parameters' => $cardDataOutput
        ];

        $outputSpeech = $question;
        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            [
                'cardData' => $cardDataOutput,
                'collection' => $collectionName
            ],
            $contextOut
        );
        return $speechResponse;
    }

    public function resetScheduler(string $collectionName) : SpeechResponse
    {
        $url = "collection/{$collectionName}/reset_scheduler";
        $requestData = ['deck' => 'Default'];
        $schedulerDataArray = $this->getResponseData($url, $requestData);

        $numCards = [
            'review' => $schedulerDataArray['review_cards'],
            'learning' => $schedulerDataArray['learning_cards'],
            'new' => $schedulerDataArray['new_cards']
        ];
        $outputSpeech = "Reset scheduler for $collectionName. You have {$numCards['new']} new cards.";

        $speechResponse = new SpeechResponse(
            $outputSpeech,
            $outputSpeech,
            ['numCards' => $numCards]
        );
        return $speechResponse;
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
