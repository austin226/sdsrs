<?php

namespace Austin226\Sdsrs;

use Austin226\Sdsrs\Exceptions\BadRequestException;

class ApiController
{
    private $ankiController;

    public function __construct(string $ankiServerUri)
    {
        $this->ankiController = new AnkiApiController($ankiServerUri);
    }

    /**
     * Performs an action, and returns the result to be
     * sent to the client.
     *
     * @param array $queryParameters must include 'action'
     *
     * @return array
     * @throws \Austin226\Sdsrs\Exceptions\HttpException
     */
    public function doAction(array $queryParameters) : array
    {
        $actionName = $this->extractParameter($queryParameters, 'action');

        if ($actionName == 'list_collections') {
            return $this->listCollections();
        } elseif ($actionName == 'select_collection') {
            return $this->selectCollection();
        }
    }

    /**
     * Extracts a parameter from the list of query parameters,
     * or throws an exception if the parameter is not found.
     *
     * @param array $queryParameters
     * @param string $paramName
     *
     * @return string
     * @throws \Austin226\Sdsrs\Exceptions\HttpException
     */
    private function extractParameter(array $queryParameters, string $paramName) : string
    {
        if (isset($queryParameters[$paramName])) {
            return $queryParameters[$paramName];
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

    private function selectCollection() : array
    {
    }
}
