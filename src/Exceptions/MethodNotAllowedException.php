<?php

namespace Austin226\Sdsrs\Exceptions;

class MethodNotAllowedException extends HttpException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        if ($code === 0) {
            $code = 405;
        }
        parent::__construct($message, $code, $previous);
    }
}
