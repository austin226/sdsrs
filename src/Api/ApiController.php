<?php

namespace Aalmond\Sdsrs\Api;

use Aalmond\Sdsrs\Anki\AnkiApiControllerInterface;
use Aalmond\Sdsrs\ApiAi\SpeechResponse;
use Aalmond\Sdsrs\Exceptions\BadRequestException;
use Aalmond\Sdsrs\Exceptions\MethodNotAllowedException;

class ApiController implements ApiControllerInterface
{
    private $ankiController;

    const ACTIONS_LIST = [
        'list_collections' => [
            'function' => 'listCollections',
            'parameters' => []
        ],
        'list_decks' => [
            'function' => 'listDecks',
            'parameters' => [
                'collection'
            ]
        ],
        'add_card' => [
            'function' => 'addCard',
            'parameters' => [
                'collection',
                'front',
                'back'
            ]
        ],
        'next_card' => [
            'function' => 'nextCard',
            'parameters' => [
                'collection',
                'deck'
            ]
        ],
        'reset_scheduler' => [
            'function' => 'resetScheduler',
            'parameters' => [
                'collection',
                'deck'
            ]
        ],
        'answer_card' => [
            'function' => 'answerCard',
            'parameters' => [
                'collection',
                'cardID',
                'answer'
            ]
        ]
        // TODO others
    ];

    public function __construct(AnkiApiControllerInterface $ankiController)
    {
        $this->ankiController = $ankiController;
    }

    /**
     * Handles request and passes control to doAction
     *
     * @param array
     * @return array
     */
    public function handleRequest(array $requestData) : array
    {
        if (!isset($requestData['result']['metadata']['intentName'])) {
            throw new BadRequestException("Invalid request.");
        }

        $intent = $requestData['result']['metadata']['intentName'];
        $speechResponse = $this->doAction($intent, $requestData['result']['metadata']);
        return json_decode(json_encode($speechResponse), true);
    }

    private function doAction(string $action, array $requestData) : SpeechResponse
    {
        if (!isset(self::ACTIONS_LIST[$action])) {
            throw new BadRequestException("Unknown action: '$action'");
        }

        $actionInfo = self::ACTIONS_LIST[$action];

        $functionName = $actionInfo['function'];
        $functionParameters = [];
        foreach ($actionInfo['parameters'] as $paramName) {
            $functionParameters[] = $this->extractParameter($requestData, $paramName);
        }

        return call_user_func_array([$this->ankiController, $functionName], $functionParameters);
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
