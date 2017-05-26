<?php

namespace Austin226\Sdsrs\tst;

use Austin226\Sdsrs\Api\ApiController;
use Austin226\Sdsrs\Exceptions\BadRequestException;
use Austin226\Sdsrs\Exceptions\MethodNotAllowedException;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    private $apiController;

    public function setUp()
    {
        $this->apiController = new ApiController('');
    }

    public function testBadMethod()
    {
        $this->expectException(MethodNotAllowedException::class);
        $this->apiController->doAction('POST', 'list_collections', []);
    }

    public function testBadAction()
    {
        $this->expectException(BadRequestException::class);
        $this->apiController->doAction('POST', 'blah', []);
    }
}
