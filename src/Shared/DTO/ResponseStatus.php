<?php

declare(strict_types=1);

namespace POS\Shared\DTO;

enum ResponseStatus: string
{
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case FAIL = 'fail';
    case ERROR = 'error';
}