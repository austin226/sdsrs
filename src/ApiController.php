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
     * @throws HttpException
     */
    public function doAction(array $queryParameters) : array
    {
        if (!isset($queryParameters['action'])) {
            throw new BadRequestException("No action name specified.");
        }

        $actionName = $queryParameters['action'];
        if ($actionName == 'list_collections') {
            return $this->listCollections();
        }
    }

    /**
     * @return list
     */
    private function listCollections() : array
    {
        return $this->ankiController->listCollections();
    }
}
