<?php

declare(strict_types=1);

namespace POS\Shared\History\Events;

use POS\Shared\History\Event;
use POS\Shared\ValueObjects\Uuid;

final class UpdateEvent extends Event
{
    public function __construct(
        public string $table,
        public Uuid $id,
        public array $from = [],
        public array $to = []
    ) {
        parent::__construct();
    }

    protected function setAction(): void
    {
        $this->action = 'update';
    }

    public function getData(): array
    {
        $response = [
            'action' => $this->getAction(),
            'table' => $this->table,
            'id' => $this->id->value
        ];

        $fields = array_unique(array_merge(array_keys($this->from), array_keys($this->to)));

        foreach ($fields as $field) {
            if (
                isset($this->from[$field])
                && isset($this->to[$field])
                && $this->from[$field] !== $this->to[$field]
            ) {
                $response['changed_fields'][] = [
                    'field' => $field,
                    'from' => $this->from[$field],
                    'to' => $this->to[$field]
                ];
            }
        }

        return $response;
    }
}
