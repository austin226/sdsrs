<?php

namespace Aalmond\Sdsrs\Exceptions;

class BadRequestException extends HttpException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        if ($code === 0) {
            $code = 400;
        }
        parent::__construct($message, $code, $previous);
    }
}
