<?php

namespace Aalmond\Sdsrs\Api;

interface ApiControllerInterface
{
    /**
     * Performs an action, and returns the result to be
     * sent to the client.
     *
     * @param string $method HTTP method used
     * @param string $action action to perform
     * @param array $requestData
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    public function doAction(string $method, string $action, array $requestData) : array;
}
