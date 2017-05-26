<?php

namespace Aalmond\Sdsrs\Api;

use Aalmond\Sdsrs\Anki\AnkiApiControllerInterface;
use Aalmond\Sdsrs\Exceptions\BadRequestException;
use Aalmond\Sdsrs\Exceptions\MethodNotAllowedException;

class ApiController implements ApiControllerInterface
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

    public function __construct(AnkiApiControllerInterface $ankiController)
    {
        $this->ankiController = $ankiController;
    }

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
     * @throws \Aalmond\Sdsrs\Exceptions\MethodNotAllowedException
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
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
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
