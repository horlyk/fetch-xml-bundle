<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\Factory;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilder;

class QueryBuilderFactory implements QueryBuilderFactoryInterface
{
    public function createBuilder(): QueryBuilder
    {
        return new QueryBuilder();
    }
}
