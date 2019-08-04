<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\Factory;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilder;

/**
 * Interface FetchXmlQueryBuilderFactoryInterface.
 */
interface QueryBuilderFactoryInterface
{
    public function createBuilder(): QueryBuilder;
}
