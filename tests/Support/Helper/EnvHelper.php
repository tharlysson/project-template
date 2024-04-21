<?php

namespace Tests\Support\Helper;

use Dotenv\Dotenv;

class EnvHelper extends \Codeception\Module
{
    public function _beforeSuite($settings = []): void
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../../');
        $dotenv->load();
    }
}
