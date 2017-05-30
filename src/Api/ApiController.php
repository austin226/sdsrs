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
        ],
        'add_card' => [
            'method' => 'POST',
            'function' => 'addCard',
            'parameters' => [
                'collection',
                'front',
                'back'
            ]
        ],
        'next_card' => [
            'method' => 'POST',
            'function' => 'nextCard',
            'parameters' => [
                'collection',
                'deck'
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

        $actionInfo = self::ACTIONS_LIST[$action];

        $this->validateMethod($method, $action, $actionInfo);

        $functionName = $actionInfo['function'];
        $functionParameters = [];
        foreach ($actionInfo['parameters'] as $paramName) {
            $functionParameters[] = $this->extractParameter($requestData, $paramName);
        }

        return call_user_func_array([$this->ankiController, $functionName], $functionParameters);
    }

    /**
     * Checks that we are using the right method for the action.
     *
     * @throws \Aalmond\Sdsrs\Exceptions\MethodNotAllowedException
     */
    private function validateMethod(string $method, string $action, array $actionInfo) : void
    {
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
}
