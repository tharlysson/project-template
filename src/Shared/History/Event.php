<?php

declare(strict_types=1);

namespace POS\Shared\History;

abstract class Event
{
    protected string $action;

    public function __construct()
    {
        $this->setAction();
    }

    abstract protected function setAction(): void;

    abstract public function getData(): array;

    public function getAction(): string
    {
        return $this->action;
    }
}
