<?php

namespace Aalmond\Sdsrs\Logging;

use Psr\Log\AbstractLogger;

class FileAppendingLogger extends AbstractLogger
{
    private $filepath;

    public function __construct(string $filepath)
    {
        $this->createFile($filepath);
        $this->filepath = $filepath;
    }

    private function createFile(string $filepath)
    {
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }

        if (!file_exists($filepath)) {
            touch($filepath);
            chmod($filepath, 0777);
        }
    }

    public function log($level, $message, array $context = [])
    {
        $contextStr = json_encode($context);
        file_put_contents($this->filepath, "[$level] $message $contextStr\n", FILE_APPEND);
    }
}
