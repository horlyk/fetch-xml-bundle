<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\Factory;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilder;

/**
 * Interface FetchXmlQueryBuilderFactoryInterface.
 */
interface QueryBuilderFactoryInterface
{
    public const DEFAULT_USE_PAGER = false;
    public const DEFAULT_ITEMS_PER_PAGE = 10;
    public const DEFAULT_ATTRIBUTE_ALIASES_AS_NAMES = true;
    public const DEFAULT_ATTRIBUTE_ALIAS_PREFIX = 'qb_';

    public function createBuilder(): QueryBuilder;
}
