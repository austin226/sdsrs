<?php

namespace Aalmond\Sdsrs\Api;

use Aalmond\Sdsrs\Anki\AnkiApiControllerInterface;
use Aalmond\Sdsrs\ApiAi\SpeechResponse;
use Aalmond\Sdsrs\Exceptions\BadRequestException;
use Aalmond\Sdsrs\Exceptions\MethodNotAllowedException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ApiController implements ApiControllerInterface, LoggerAwareInterface
{
    private $ankiController;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    const ACTIONS_LIST = [
        'list_collections' => [
            'function' => 'listCollections',
            'parameters' => []
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
            ]
        ],
        'reset_scheduler' => [
            'function' => 'resetScheduler',
            'parameters' => [
                'collection',
            ]
        ],
        'answer_card' => [
            'function' => 'answerCard',
            'parameters' => [
                'collection',
                'card_id',
                'answer_ease'
            ]
        ]
        // TODO others
    ];

    public function __construct(AnkiApiControllerInterface $ankiController)
    {
        $this->ankiController = $ankiController;
        $this->setLogger(new NullLogger());
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->ankiController->setLogger($logger);
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
        $this->logger->debug("Received request: ".json_encode($requestData));

        $intent = $requestData['result']['metadata']['intentName'];
        $speechResponse = $this->doAction($intent, $requestData['result']);
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
            $this->logger->debug("Extracting parameter '$paramName'", ['parameters' => $requestData['parameters']]);
            $paramValue = $this->extractParameter($requestData['parameters'], $paramName);
            $this->logger->debug("Extracted value: '".json_encode($paramValue)."'");
            $functionParameters[] = $this->extractParameter($requestData['parameters'], $paramName);
        }

        return call_user_func_array([$this->ankiController, $functionName], $functionParameters);
    }

    /**
     * Extracts a parameter from the list of query parameters,
     * or throws an exception if the parameter is not found.
     *
     * @param array $requestParameters
     * @param string $paramName
     *
     * @return string
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    private function extractParameter(array $requestParameters, string $paramName) : string
    {
        if (isset($requestParameters[$paramName])) {
            return strtolower($requestParameters[$paramName]);
        }

        throw new BadRequestException("Parameter '$paramName' is missing.");
    }
}
