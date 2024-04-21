<?php

namespace POS\Shared\Exceptions;

use Exception;

class InvalidValueException extends Exception
{
    protected const MESSAGE = 'invalid value: \'%s\', for class \'%s\'';

    public function __construct(string $class, mixed $value)
    {
        parent::__construct(message: sprintf(self::MESSAGE, $value, $class));
    }
}