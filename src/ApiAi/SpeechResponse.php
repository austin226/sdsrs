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
        ?array $contextOut = null,
        ?string $source = null,
        ?array $followupEvent = null
    ) {
        $this->speech = $speech;
        $this->displayText = $displayText;
        $this->data = $data;
        $this->contextOut = $contextOut;
        $this->source = $source;
        $this->followupEvent = $followupEvent;
    }

    public function jsonSerialize()
    {
        $jsonData = [
            'speech' => $this->speech,
            'displayText' => $this->displayText,
            'data' => $this->data
        ];

        if (isset($this->contextOut)) {
            $jsonData['contextOut'] = $this->contextOut;
        }
        if (isset($this->source)) {
            $jsonData['source'] = $this->source;
        }
        if (isset($this->followupEvent)) {
            $jsonData['followupEvent'] = $this->followupEvent;
        }

        return $jsonData;
    }
}
