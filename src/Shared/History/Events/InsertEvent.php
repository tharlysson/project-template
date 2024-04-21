<?php

declare(strict_types=1);

namespace POS\Shared\History\Events;

use POS\Shared\History\Event;
use POS\Shared\ValueObjects\Uuid;

final class InsertEvent extends Event
{
    public function __construct(
        public string $table,
        public Uuid $id,
    ) {
        parent::__construct();
    }

    protected function setAction(): void
    {
        $this->action = 'insert';
    }

    public function getData(): array
    {
        return [
            'action' => $this->getAction(),
            'table' => $this->table,
            'id' => $this->id->value
        ];
    }
}
