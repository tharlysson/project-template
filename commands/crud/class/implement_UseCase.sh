#!/bin/bash

echo "Implementando classe $base_dir/$domain/Application/UseCase/${entity}UseCase.php"
echo "<?php

namespace $sistema\\${domain}\Application\UseCase;

use $sistema\\${domain}\Application\DTO\\${entity}Listing;
use $sistema\\${domain}\Domain\Entity\\${entity};
use $sistema\\${domain}\Domain\Repository\\${entity}Repository;
use $sistema\\${domain}\Presentation\DTO\\${entity}CreateRequest;
use $sistema\\${domain}\Presentation\DTO\\${entity}UpdateRequest;
use $sistema\Shared\Exceptions\NotFoundException;
use $sistema\Shared\ValueObjects\Uuid;

final readonly class ${entity}UseCase
{
    public function __construct(private ${entity}Repository \$bankRepository)
    {
    }

    public function store(${entity}CreateRequest \$request): Uuid
    {
        \$entity = new ${entity}(
            name: \$request->name,
            active: true
        );

        return \$this->bankRepository->store(\$entity);
    }

    /**
     * @throws NotFoundException
     */
    public function update(${entity}UpdateRequest \$request): bool
    {
        \$entity = \$this->bankRepository->show(\$request->id);

        if (!\$entity) {
            throw new NotFoundException(\"${entity} not found\");
        }

        \$entity->name = \$request->name;
        \$entity->active = \$request->active;

        return \$this->bankRepository->update(\$entity);
    }

    public function destroy(Uuid \$id): bool
    {
        return \$this->bankRepository->destroy(\$id);
    }

    public function show(Uuid \$id): ?${entity}
    {
        return \$this->bankRepository->show(\$id);
    }

    /**
     * @return ${entity}Listing[]
     */
    public function list(array \$filters): array
    {
        return \$this->bankRepository->list(${entity}Listing::class, \$filters);
    }

    public function countList(array \$filters): int
    {
        return \$this->bankRepository->countList(\$filters);
    }
}" > "$base_dir/$domain/Application/UseCase/${entity}UseCase.php"