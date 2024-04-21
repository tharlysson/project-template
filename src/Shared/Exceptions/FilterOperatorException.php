<?php

declare(strict_types=1);

namespace POS\Shared\Exceptions;

use Exception;

class FilterOperatorException extends Exception
{
    public function __construct(string $value)
    {
        parent::__construct("Operador '$value' não suportado.");
    }
}
