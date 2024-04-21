<?php

namespace POS\Shared\Helpers;

use Illuminate\Validation\Factory;

final class Validator
{
    public static function init(): Factory
    {
        return new Factory(trans(locale: getenv('APP_LANGUAGE') ?: 'en'));
    }
}