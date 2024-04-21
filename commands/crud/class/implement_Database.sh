#!/bin/bash

echo "Implementando classe $base_dir/$domain/Infra/Repository/Database/${entity}Doctrine.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\${domain}\Infra\Repository\Database;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use $sistema\\${domain}\Domain\Entity\\${entity};
use $sistema\\${domain}\Domain\Repository\\${entity}Repository;
use $sistema\Shared\Connector\Doctrine;
use $sistema\Shared\ValueObjects\Uuid;

final class ${entity}Doctrine extends Doctrine implements ${entity}Repository
{
    protected string \$tableName = '$table_name';

    protected array \$dateTimeAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array \$boolAttributes = [
        'active',
    ];

    protected array \$hiddenAttributes = [
        'deleted_at',
    ];

    protected array \$uuidAttributes = [
        'id',
        'project_id',
    ];

    private function findQueryBase(QueryBuilder \$queryBuilder, string \$fields = '*'): void
    {
        \$queryBuilder->select(\$fields)
            ->from(\$this->tableName, '${table_alias}')
            ->where(\$queryBuilder->expr()->isNull('${table_alias}.deleted_at'));
    }

    /**
     * @throws Exception
     */
    public function show(Uuid \$uuid): ?${entity}
    {
        \$queryBuilder = \$this->connection->createQueryBuilder();

        \$this->findQueryBase(\$queryBuilder);

        \$queryBuilder->andWhere(\"${table_alias}.\$this->primaryKey = :id\");

        \$entity = \$this->connection->executeQuery(
            \$queryBuilder->getSQL(),
            ['id' => \$uuid]
        )->fetchAssociative() ?: [];

        return \$this->arrayToObject(
            \$entity,
            ${entity}::class
        ) ?: null;
    }

    /**
     * @throws Exception
     */
    public function list(string \$dtoListing, array \$filters = []): array
    {
        \$queryBuilder = \$this->connection->createQueryBuilder();

        \$this->findQueryBase(\$queryBuilder, \$filters['fields'] ?? '*');

        \$filters['orderField'] = '${table_alias}.created_at';

        \$this->createFilterListing(\$queryBuilder, \$filters);

        \$itens = \$this->connection->executeQuery(
            \$queryBuilder->getSQL(),
        )->fetchAllAssociative() ?: [];

        return \$this->arrayToObject(
            \$itens,
            \$dtoListing
        );
    }

    /**
     * @throws Exception
     */
    public function countList(array \$filters = []): int
    {
        \$queryBuilder = \$this->connection->createQueryBuilder();

        \$this->findQueryBase(\$queryBuilder, 'count(1) as total');

        \$this->createFiltersHTTP(\$queryBuilder, \$filters);

        return \$this->connection->executeQuery(
            \$queryBuilder->getSQL(),
        )->fetchAssociative()['total'] ?: 0;
    }
}" > "$base_dir/$domain/Infra/Repository/Database/${entity}Doctrine.php"