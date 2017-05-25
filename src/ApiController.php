<?php

namespace Austin226\Sdsrs;

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
        ]
    ];

    public function __construct(string $ankiServerUri)
    {
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
        $actionInfo = self::ACTIONS_LIST[$action];
        if ($actionInfo['method'] != $method) {
            $errMsg = <<<TEXT
Method {$method} not allowed for action {$action}. Use {$actionInfo['method']}.
TEXT;
            throw new MethodNotAllowedException($errMsg);
        }
        if ($actionName == 'list_collections') {
            return $this->listCollections();
        } elseif ($actionName == 'select_collection') {
            $collectionName = $this->extractParameter($requestData, 'collectionName');
            $this->selectCollection($collectionName);
        }

        return [];
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

    /**
     * Selects a collection, or throws an exception if the collection is not found.
     */
    private function selectCollection(string $collectionName) : void
    {
        $this->ankiController->setCollection($collectionName);
    }
}
