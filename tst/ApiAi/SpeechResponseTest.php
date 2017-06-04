<?php

namespace Aalmond\Sdsrs\tst\ApiAi;

use Aalmond\Sdsrs\ApiAi\SpeechResponse;
use PHPUnit\Framework\TestCase;

class SpeechResponseTest extends TestCase
{
    public function testNormalResponse()
    {
        $speech = 'this is speech';
        $displayText = 'this is display text';
        $data = ['abc' => 123];
        $contextOut = ['def' => 456];
        $source = 'a person';
        $followupEvent = [];

        $response = new SpeechResponse(
            $speech,
            $displayText,
            $data,
            $contextOut,
            $source,
            $followupEvent
        );

        $jsonDecoded = json_decode(json_encode($response), true);

        $this->assertEquals([
            'speech' => $speech,
            'displayText' => $displayText,
            'data' => $data,
            'contextOut' => $contextOut,
            'source' => $source,
            'followupEvent' => $followupEvent
        ], $jsonDecoded);
    }
}
