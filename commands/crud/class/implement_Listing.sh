#!/bin/bash

echo "Implementando classe $base_dir/$domain/Application/DTO/${entity}Listing.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\${domain}\Application\DTO;

use DateTime;
use JsonSerializable;
use $sistema\Shared\ValueObjects\Uuid;

final readonly class ${entity}Listing implements JsonSerializable
{
    public function __construct(
        public Uuid \$id,
        public string \$name,
        public bool \$active,
        public DateTime \$createdAt,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => \$this->id->value,
            'name' => \$this->name,
            'active' => \$this->active,
            'createdAt' => \$this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}" > "$base_dir/$domain/Application/DTO/${entity}Listing.php"