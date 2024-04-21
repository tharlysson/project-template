<?php

declare(strict_types=1);

namespace POS\Shared\Connector;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use POS\Shared\Connector\Filters\Abstracts\Filter;
use POS\Shared\Domain\Entity;
use POS\Shared\Domain\Enum;
use POS\Shared\ValueObjects\Uuid;
use ReflectionClass;

abstract class Doctrine extends Filter
{
    use DoctrinePersist;

    protected Connection|null $connection = null;

    protected string $tableName;

    protected string $uuid;

    protected string $primaryKey = 'id';

    protected array $uuidAttributes = [];

    protected array $integerAttributes = [];

    protected array $boolAttributes = [];

    protected array $dateTimeAttributes = [];

    protected array $enumAttributes = [];

    protected array $hiddenAttributes = [];

    public function __construct() {
        $connectionParams = [
            'dbname' => getenv('DB_DATABASE'),
            'user' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'host' => getenv('DB_HOST'),
            'port' => (int)getenv('DB_PORT'),
            'driver' => getenv('DB_DRIVER'),
        ];

        $this->connection = DriverManager::getConnection($connectionParams);
    }

    private function dataConvertPersist(string $entityAttribute, Entity $entity, QueryBuilder $qb): void
    {
        if ($entity->$entityAttribute instanceof DateTime) {
            $this->handleDateTimeAttribute($entityAttribute, $entity, $qb);
        } elseif (is_bool($entity->$entityAttribute)) {
            $this->handleBoolAttribute($entityAttribute, $entity, $qb);
        } elseif ($entity->$entityAttribute instanceof Enum) {
            $this->handleEnumAttribute($entityAttribute, $entity, $qb);
        } else {
            $this->handleOtherAttribute($entityAttribute, $entity, $qb);
        }
    }

    private function handleDateTimeAttribute(string $entityAttribute, Entity $entity, QueryBuilder $qb): void
    {
        $qb->setParameter($entityAttribute, $entity->$entityAttribute->format('Y-m-d H:i:s'));
    }

    private function handleBoolAttribute(string $entityAttribute, Entity $entity, QueryBuilder $qb): void
    {
        $qb->setParameter($entityAttribute, $entity->$entityAttribute ? 1 : 0);
    }

    private function handleEnumAttribute(string $entityAttribute, Entity $entity, QueryBuilder $qb): void
    {
        $qb->setParameter($entityAttribute, $entity->$entityAttribute->value);
    }

    private function handleOtherAttribute(string $entityAttribute, Entity $entity, QueryBuilder $qb): void
    {
        $qb->setParameter($entityAttribute, $entity->$entityAttribute);
    }

    protected function convertArraySnakeToCamelCase(array $array): array
    {
        $aux = [];

        foreach ($array as $key => $value) {
            $aux[$this->snakeToCamelCase($key)] = $value;
        }

        return $aux;
    }

    protected function snakeToCamelCase(string $string): string
    {
        $string = str_replace('_', '', ucwords($string, '_'));
        return lcfirst($string);
    }

    protected function camelCaseToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    protected function arrayToObject(
        array $dataResult,
        string $className,
        ?array $formatCallback = null
    ): array|object {
        if (empty($dataResult)) {
            return [];
        }

        $response = [];
        $dimensoes = count($dataResult, COUNT_RECURSIVE) - count($dataResult);

        if ($dimensoes == 0) {
            $dataResult = $this->extractProperties($dataResult, $formatCallback, $className);

            return new $className(...$dataResult);
        } else {
            foreach ($dataResult as $element) {
                $element = $this->extractProperties($element, $formatCallback, $className);

                $response[] = new $className(...$element);
            }
        }
        return $response;
    }

    private function extractProperties(mixed $element, ?array $formatCallback, string $className): array
    {
        $element = $this->convertArraySnakeToCamelCase($element);
        $this->transformDataToValueObjects($element, $formatCallback);

        $listProperty = [];
        $reflection = new ReflectionClass($className);

        foreach ($reflection->getProperties() as $property) {
            $listProperty[] = $property->getName();
        }

        $listPropertyFlipped = array_flip($listProperty);
        return array_intersect_key($element, $listPropertyFlipped);
    }

    private function convertDataType(array &$data): void
    {
        foreach ($this->hiddenAttributes as $value) {
            $value = $this->snakeToCamelCase($value);
            unset($data[$value]);
        }

        foreach ($this->boolAttributes as $value) {
            $value = $this->snakeToCamelCase($value);
            if (!isset($data[$value])) {
                $data[$value] = false;
                continue;
            }
            $data[$value] = $data[$value] == 1;
        }

        foreach ($this->dateTimeAttributes as $value) {
            $value = $this->snakeToCamelCase($value);
            if (!isset($data[$value])) {
                continue;
            }
            $data[$value] = $data[$value] ? new DateTime($data[$value]) : null;
        }

        foreach ($this->integerAttributes as $value) {
            $value = $this->snakeToCamelCase($value);
            if (!isset($data[$value])) {
                continue;
            }
            $data[$value] = (int)$data[$value];
        }

        foreach ($this->enumAttributes as $field => $class) {
            $field = $this->snakeToCamelCase($field);
            if (!isset($data[$field])) {
                continue;
            }
            $data[$field] = $class::from($data[$field]);
        }

        foreach ($this->uuidAttributes as $value) {
            $value = $this->snakeToCamelCase($value);
            $data[$value] = new Uuid($data[$value]);
        }
    }

    private function transformDataToValueObjects(array &$data, ?array $formatCallback): void
    {
        if (!empty($formatCallback)) {
            $intersection = $formatCallback($data);
            $this->convertDataType($data);
            $data = array_merge($data, $intersection);
        } else {
            $this->convertDataType($data);
        }
    }
}