<?php

namespace Aalmond\Sdsrs\Exceptions;

class ResourceNotFoundException extends HttpException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        if ($code === 0) {
            $code = 404;
        }
        parent::__construct($message, $code, $previous);
    }
}
