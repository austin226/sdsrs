<?php

namespace Austin226\Sdsrs\tst;

use PHPUnit\Framework\TestCase;
use Austin226\Sdsrs\ApiController;

class ApiControllerTest extends TestCase
{
    public function setUp()
    {
        $this->apiController = new ApiController('');
    }

    public function testDummy()
    {
        $this->assertEquals(4, 2+2);
    }
}
