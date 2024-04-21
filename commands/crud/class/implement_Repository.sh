#!/bin/bash

echo "Implementando classe $base_dir/$domain/Domain/Repository/${entity}Repository.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\${domain}\Domain\Repository;

use $sistema\\${domain}\Domain\Entity\\${entity};
use $sistema\Shared\ValueObjects\Uuid;

interface ${entity}Repository
{

    /**
     * @param ${entity} \$bank
     *
     * @return Uuid
     */
    public function store(${entity} \$bank): Uuid;

    /**
     * @param ${entity} \$bank
     *
     * @return bool
     */
    public function update(${entity} \$bank): bool;

    /**
     * @param Uuid \$uuid
     *
     * @return bool
     */
    public function destroy(Uuid \$uuid): bool;

    /**
     * @param Uuid \$uuid
     *
     * @return ${entity}|null
     */
    public function show(Uuid \$uuid): ?${entity};

    /**
     * @param string \$dtoListing
     * @param array \$filters
     *
     * @return array
     */
    public function list(string \$dtoListing, array \$filters = []): array;

    /**
     * @param array \$filters
     *
     * @return int
     */
    public function countList(array \$filters = []): int;
}" > "$base_dir/$domain/Domain/Repository/${entity}Repository.php"