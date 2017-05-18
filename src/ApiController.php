<?php

namespace Austin226\Sdsrs;

class ApiController
{
    private $ankiController;

    public function __construct($ankiServerUri)
    {
        $this->ankiController = new AnkiApiController($ankiServerUri);
    }

    /**
     * Performs an action, and returns the result to be
     * sent to the client.
     *
     * @param string $actionName
     * @param array $queryParameters
     *
     * @return mixed
     */
    public function doAction($actionName, $queryParameters)
    {
        if ($actionName == 'list_controllers') {
            return $this->listControllers();
        }
    }

    /**
     * @return list
     */
    private function listCollections()
    {
        return $this->ankiController->listCollections();
    }
}
