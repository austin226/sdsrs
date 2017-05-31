<?php

namespace Aalmond\Sdsrs\tst;

use Aalmond\Sdsrs\Anki\AnkiApiController;
use Aalmond\Sdsrs\Api\ApiController;
use Aalmond\Sdsrs\Exceptions\BadRequestException;
use Aalmond\Sdsrs\Exceptions\MethodNotAllowedException;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    private $apiController;

    public function setUp()
    {
        $ankiController = new AnkiApiController('');
        $this->apiController = new ApiController($ankiController);
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
