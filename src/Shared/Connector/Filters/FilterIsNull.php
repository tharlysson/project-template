<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters;

use POS\Shared\Connector\Filters\Abstracts\AbstractFilterSearch;
use POS\Shared\Connector\Filters\Abstracts\Filter;

final class FilterIsNull extends AbstractFilterSearch
{
    public function __construct(
        protected string $attribute,
    ) {
        parent::__construct(Filter::OPERATOR_NULL, $attribute);
    }
}
