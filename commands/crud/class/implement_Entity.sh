#!/bin/bash

echo "Implementando classe $base_dir/$domain/Domain/Entity/$entity.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\${domain}\Domain\Entity;

use DateTime;
use JsonSerializable;
use $sistema\Shared\Domain\Entity;
use $sistema\Shared\ValueObjects\Uuid;

final class $entity implements Entity, JsonSerializable
{
    public function __construct(
        public string \$name,
        public readonly Uuid \$id = new Uuid(),
        public readonly Uuid \$projectId = new Uuid(),
        public bool \$active = true,
        public readonly DateTime \$createdAt = new DateTime(),
        public ?DateTime \$updatedAt = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => \$this->id->value,
            'name' => \$this->name,
            'active' => \$this->active,
            'createdAt' => \$this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => \$this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}" > "$base_dir/$domain/Domain/Entity/$entity.php"