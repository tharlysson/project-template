<?php

namespace POS\Shared\Connector;

use POS\Shared\Domain\Entity;
use POS\Shared\Domain\ValueObject;
use POS\Shared\History\Events\DeleteEvent;
use POS\Shared\History\Events\InsertEvent;
use POS\Shared\History\Events\UpdateEvent;
use POS\Shared\History\History;
use POS\Shared\ValueObjects\Uuid;
use ReflectionClass;
use ReflectionProperty;

trait DoctrinePersist
{
    public function store(Entity $entity): Uuid
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert($this->tableName);

        $attributes = $this->getPropertiesNames($entity);
        $attributes = array_diff($attributes, $this->hiddenAttributes);

        foreach ($attributes as $entityAttribute) {
            $dbAtributo = $this->camelCaseToSnakeCase($entityAttribute);

            $this->dataConvertPersist($entityAttribute, $entity, $qb);

            $qb->setValue($dbAtributo, ":$entityAttribute");
        }

        $this->connection->executeStatement($qb, $qb->getParameters());

        History::setEvent(new InsertEvent($this->tableName, $entity->{$this->primaryKey}));

        return $entity->{$this->primaryKey};
    }

    public function update(Entity $entity): bool
    {
        $before = $this->returnLine($entity->{$this->primaryKey});

        $qb = $this->connection->createQueryBuilder();
        $qb->update($this->tableName);

        $attributes = $this->getPropertiesNames($entity);
        $attributes = array_diff($attributes, $this->hiddenAttributes);

        foreach ($attributes as $entityAttribute) {
            $dbAtributo = $this->camelCaseToSnakeCase($entityAttribute);

            if ($dbAtributo == $this->primaryKey) {
                $qb->where($dbAtributo . ' = :' . $entityAttribute);
                $qb->setParameter($entityAttribute, $entity->$entityAttribute);
                continue;
            }

            $this->dataConvertPersist($entityAttribute, $entity, $qb);

            $qb->set($dbAtributo, ":$entityAttribute");
        }

        $return = (bool)$this->connection->executeStatement($qb, $qb->getParameters());

        $after = $this->returnLine($entity->{$this->primaryKey});

        HIstory::setEvent(new UpdateEvent($this->tableName, $entity->{$this->primaryKey}, $before, $after));

        return $return;
    }

    private function returnLine(Uuid $id): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('*')
            ->from($this->tableName)
            ->where("$this->primaryKey = :id");

        return $this->connection->executeQuery(
            $queryBuilder->getSQL(),
            ["id" => $id->value]
        )->fetchAssociative() ?: [];
    }

    private function getPropertiesNames(Entity $entity): array
    {
        $reflectionClass = new ReflectionClass($entity);
        $properties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PUBLIC
        );

        $propertiesArray = [];

        foreach ($properties as $property) {
            $value = $property->getValue($entity);

            if ($value === null) {
                continue;
            } else {
                if (!$value instanceof ValueObject) {
                    $propertiesArray[] = $property->getName();
                }
            }
        }

        return $propertiesArray;
    }

    public function destroy(Uuid $id): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update($this->tableName)
            ->set('deleted_at', ':now')
            ->where($this->primaryKey . ' = :id');

        $return = (bool)$this->connection->executeStatement(
            $qb,
            [
                'now' => date('Y-m-d H:i:s'),
                'id' => $id->value
            ]
        );

        History::setEvent(new DeleteEvent($this->tableName, $id));

        return $return;
    }
}
