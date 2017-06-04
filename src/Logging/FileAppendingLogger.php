<?php

namespace Aalmond\Sdsrs\Logging;

use Psr\Log\AbstractLogger;

class FileAppendingLogger extends AbstractLogger
{
    private $filepath;

    public function __construct($filepath)
    {
        if (!file_exists($filepath)) {
            touch($filepath);
        }
        $this->filepath = $filepath;
    }

    public function log($level, $message, array $context = [])
    {
        $contextStr = json_encode($context);
        file_put_contents("[$level] $message $contextStr\n", $this->filepath, FILE_APPEND);
    }
}
