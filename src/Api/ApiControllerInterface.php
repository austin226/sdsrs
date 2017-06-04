<?php

namespace Aalmond\Sdsrs\Api;

interface ApiControllerInterface
{
    /**
     * Performs an action, and returns the result to be
     * sent to the client.
     *
     * @param array $requestData
     *
     * @return array
     * @throws \Aalmond\Sdsrs\Exceptions\HttpException
     */
    public function handleRequest(array $requestData) : array;
}
