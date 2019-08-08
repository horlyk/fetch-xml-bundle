<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilder;

class FactoryTest extends AbstractQueryBuilderTest
{
    public function testFactory()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }
}
