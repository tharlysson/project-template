<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters\Abstracts;

use POS\Shared\Connector\Filters\FilterEqual;
use POS\Shared\Connector\Filters\FilterIsNotNull;
use POS\Shared\Connector\Filters\FilterIsNull;
use POS\Shared\Exceptions\FilterTypeException;
use POS\Shared\Exceptions\FilterOperatorException;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class Filter
{
    public const OPERATOR_NOT_IN = 'notIn';
    public const OPERATOR_EQUAL_OR_NULL = 'equalOrNull';
    public const OPERATOR_IN = 'in';
    public const OPERATOR_LIKE = 'like';
    public const OPERATOR_NOT_EQUAL = 'notEqual';
    public const OPERATOR_LESS_THAN = 'lessThan';
    public const OPERATOR_LESS_THAN_OR_EQUAL = 'lessThanOrEqual';
    public const OPERATOR_CONDITIONS_OR = 'or';
    public const OPERATOR_GREATER_THAN_OR_EQUAL = 'greaterThanOrEqual';
    public const OPERATOR_EQUAL = 'equal';
    public const OPERATOR_NOT_NULL = 'isNotNull';
    public const OPERATOR_NULL = 'isNull';
    public const OPERATOR_GREATER_THAN = 'greaterThan';
    public const OPERATOR_EQUAL_OR_NOT_NULL = 'equalOrNotNull';
    public const OPERATOR_LIST = 'list';
    private const COMPARATOR_AND = 'and';
    private const COMPARATOR_OR = 'or';

    /**
     * Adiciona filtros a em um objeto QueryBuilder
     *
     * @param QueryBuilder $queryBuilder
     * @param AbstractFilterConditional|AbstractFilterSearch|AbstractFilterSearchValue[] $filters
     *
     * @throws FilterOperatorException|FilterTypeException
     * @return void
     */
    private function addFilter(QueryBuilder $queryBuilder, array $filters): void
    {
        foreach ($filters as $filter) {
            $expressionBuilder = $queryBuilder->expr();
            $comparator = self::COMPARATOR_OR;
            $conditions = [];

            switch ($filter->getOperator()) {
                case self::OPERATOR_CONDITIONS_OR:
                    foreach ($filter->getValue() as $value) {
                        $conditions[] = $this->transformFilterInExpressionBuilder($expressionBuilder, $value);
                    }
                    break;
                case self::OPERATOR_EQUAL_OR_NULL:
                    $conditions[] = $this->transformFilterInExpressionBuilder(
                        $expressionBuilder,
                        new FilterEqual($filter->getAttribute(), $filter->getValue())
                    );
                    $conditions[] = $this->transformFilterInExpressionBuilder(
                        $expressionBuilder,
                        new FilterIsNull($filter->getAttribute())
                    );
                    break;
                case self::OPERATOR_EQUAL_OR_NOT_NULL:
                    $conditions[] = $this->transformFilterInExpressionBuilder(
                        $expressionBuilder,
                        new FilterEqual($filter->getAttribute(), $filter->getValue())
                    );
                    $conditions[] = $this->transformFilterInExpressionBuilder(
                        $expressionBuilder,
                        new FilterIsNotNull($filter->getAttribute())
                    );
                    break;
                case self::OPERATOR_LIST:
                    $queryBuilder
                        ->setFirstResult($filter->getValue()['first_result'])
                        ->setMaxResults($filter->getValue()['max_result'])
                        ->orderBy($filter->getValue()['field'], $filter->getValue()['order']);
                    break;
                default:
                    $comparator = self::COMPARATOR_AND;
                    $conditions[] = $this->transformFilterInExpressionBuilder($expressionBuilder, $filter);
                    break;
            }

            if (!empty($conditions)) {
                $queryBuilder->andWhere(
                    call_user_func_array(
                        [$expressionBuilder, $comparator],
                        $conditions
                    )
                );
            }
        }
    }

    private function transformFilterInExpressionBuilder(
        ExpressionBuilder $expressionBuilder,
        AbstractFilterConditional|AbstractFilterSearch|AbstractFilterSearchValue $filter
    ): string {
        return match ($filter->getOperator()) {
            self::OPERATOR_NULL => $expressionBuilder->isNull($filter->getAttribute()),
            self::OPERATOR_NOT_NULL => $expressionBuilder->isNotNull($filter->getAttribute()),
            self::OPERATOR_IN => $expressionBuilder->in($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_EQUAL => $expressionBuilder->eq($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_LIKE => $expressionBuilder->like($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_NOT_IN => $expressionBuilder->notIn($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_LESS_THAN => $expressionBuilder->lt($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_NOT_EQUAL => $expressionBuilder->neq($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_GREATER_THAN => $expressionBuilder->gt($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_LESS_THAN_OR_EQUAL => $expressionBuilder->lte($filter->getAttribute(), $filter->getValue()),
            self::OPERATOR_GREATER_THAN_OR_EQUAL =>
                $expressionBuilder->gte($filter->getAttribute(), $filter->getValue()),
            default => throw new FilterOperatorException($filter->getOperator())
        };
    }

    protected function createFiltersHTTP(QueryBuilder $queryBuilder, array $data): void
    {
        $filters = [];

        foreach ($data as $code => $value) {
            if (is_numeric($code)) {
                continue;
            }

            $aux = explode("|", $code);
            $operator = $aux[0];
            if (count($aux) > 1) {
                list($operator, $attribute) = explode("|", $code);
            }
            $class = "POS\\Shared\\Connector\\Filters\\Filter" . ucfirst($operator);
            if (class_exists($class) && isset($attribute)) {
                if (
                    is_subclass_of($class, 'POS\\Shared\\Connector\\Filters\\Abstracts\\AbstractFilterSearchValue')
                ) {
                    if (!$value) {
                        continue;
                    }
                    $filters[] = new $class($attribute, $value);
                } elseif (
                    is_subclass_of($class, 'POS\\Shared\\Connector\\Filters\\Abstracts\\AbstractFilterConditional')
                ) {
                    $filters[] = new $class($value);
                } elseif (
                    is_subclass_of($class, 'POS\\Shared\\Connector\\Filters\\Abstracts\\AbstractFilterSearch')
                ) {
                    $filters[] = new $class($attribute);
                }
            }
        }
        $this->addFilter($queryBuilder, $filters);
    }


    protected function createFilterListing(QueryBuilder $queryBuilder, array $filters = []): void
    {
        $limit = min(!isset($filters['limit']) || (int)$filters['limit'] < 1 ? 30 : (int)$filters['limit'], 500);
        $page = !isset($filters['page']) ? 1 : (max((int)$filters['page'], 1));
        $order = !isset($filters['order']) || strtoupper($filters['order']) != 'ASC' ? 'DESC' : 'ASC';
        $orderField = $filters['orderField'] ?? 'id';

        $filter['list'] = [
            'first_result' => $page > 1 ? ($page - 1) * $limit : 0,
            'max_result' => $limit,
            'order' => $order,
            'field' => $orderField
        ];

        $filters = array_merge($filter, $filters);
        $this->createFiltersHTTP($queryBuilder, $filters);
    }
}
