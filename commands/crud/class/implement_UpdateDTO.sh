#!/bin/bash

echo "Implementando classe $base_dir/$domain/Presentation/DTO/${entity}UpdateRequest.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\$domain\Presentation\DTO;

use $sistema\Shared\ValueObjects\Uuid;

final readonly class ${entity}UpdateRequest
{
    public function __construct(
        public Uuid \$id,
        public string \$name,
        public bool \$active = true
    ) {
    }
}" > "$base_dir/$domain/Presentation/DTO/${entity}UpdateRequest.php"