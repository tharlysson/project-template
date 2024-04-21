#!/bin/bash

echo "Implementando classe $base_dir/$domain/Presentation/DTO/${entity}CreateRequest.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\$domain\Presentation\DTO;

final readonly class ${entity}CreateRequest
{
    public function __construct(
        public string \$name,
        public bool \$active = true
    ) {
    }
}" > "$base_dir/$domain/Presentation/DTO/${entity}CreateRequest.php"