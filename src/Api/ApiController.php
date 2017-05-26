<?php

namespace Austin226\Sdsrs\Api;

use Austin226\Sdsrs\Anki\AnkiApiController;
use Austin226\Sdsrs\Exceptions\BadRequestException;
use Austin226\Sdsrs\Exceptions\MethodNotAllowedException;

class ApiController
{
    private $ankiController;

    const ACTIONS_LIST = [
        'list_collections' => [
            'method' => 'GET',
            'function' => 'listCollections',
            'parameters' => []
        ],
        'list_decks' => [
            'method' => 'GET',
            'function' => 'listDecks',
            'parameters' => [
                'collection'
            ]
        ]
        // TODO others
    ];

    public function __construct(string $ankiServerUri)
    {
        // TODO accept an interface so we can mock the AnkiApiController
        $this->ankiController = new AnkiApiController($ankiServerUri);
    }

    /**
     * Performs an action, and returns the result to be
     * sent to the client.
     *
     * @param string $method HTTP method used
     * @param string $action action to perform
     * @param array $requestData
     *
     * @return array
     * @throws \Austin226\Sdsrs\Exceptions\HttpException
     */
    public function doAction(string $method, string $action, array $requestData) : array
    {
        if (!isset(self::ACTIONS_LIST[$action])) {
            throw new BadRequestException("Unknown action: '$action'");
        }

        $this->validateMethod($method, $action);

        if ($action == 'list_collections') {
            return $this->listCollections();
        } elseif ($action == 'list_decks') {
            $collectionName = $this->extractParameter($requestData, 'collection');
            return $this->listDecks($collectionName);
        }

        return [];
    }

    /**
     * Checks that we are using the right method for the action.
     *
     * @throws \Austin226\Sdsrs\Exceptions\MethodNotAllowedException
     */
    private function validateMethod(string $method, string $action) : void
    {
        $actionInfo = self::ACTIONS_LIST[$action];
        if ($actionInfo['method'] != $method) {
            $errMsg = <<<TEXT
Method {$method} not allowed for action {$action}. Use {$actionInfo['method']}.
TEXT;
            throw new MethodNotAllowedException($errMsg);
        }
    }

    /**
     * Extracts a parameter from the list of query parameters,
     * or throws an exception if the parameter is not found.
     *
     * @param array $requestData
     * @param string $paramName
     *
     * @return string
     * @throws \Austin226\Sdsrs\Exceptions\HttpException
     */
    private function extractParameter(array $requestData, string $paramName) : string
    {
        if (isset($requestData[$paramName])) {
            return $requestData[$paramName];
        }

        throw new BadRequestException("Parameter '$paramName' is missing.");
    }

    /**
     * @return list
     */
    private function listCollections() : array
    {
        return $this->ankiController->listCollections();
    }

    private function listDecks(string $collectionName) : array
    {
        // TODO
        return $this->ankiController->listDecks($collectionName);
    }

    private function createDeck(string $collectionName, string $deckName) : boolean
    {
        // TODO
    }

    private function addCard(
        string $collectionName,
        string $deckName,
        string $front,
        string $back,
        array $metadata
    ) : boolean {
        // TODO
        // return new card UUID?
    }

    private function nextCard() : array
    {
        // TODO
        // return text with metadata
    }

    private function answerCard(string $answer) : boolean
    {
        // TODO
    }
}
