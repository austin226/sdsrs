<?php

namespace Aalmond\Sdsrs\ApiAi;

class SpeechResponse implements \JsonSerializable
{
    private $speech;
    private $displayText;
    private $data;
    private $contextOut;
    private $source;
    private $followupEvent;

    public function __construct(
        string $speech,
        string $displayText,
        array $data,
        array $contextOut = [],
        string $source = '',
        array $followupEvent = []
    ) {
        $this->speech = $speech;    
    }

    public function jsonSerialize()
    {
    }
}
