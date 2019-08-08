<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Exception\QueryBuilderException;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Attribute;

class ExceptionTest extends AbstractQueryBuilderTest
{
    public function testEntityNameNotGiven()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();

        $this->expectException(QueryBuilderException::class);

        $queryBuilder->getQuery();
    }

    public function testEmptyAliasPrefix()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();

        $queryBuilder
            ->setEntity('user')
            ->setAttributes([])
            ->setAttributeAliasPrefix('');

        $this->expectException(QueryBuilderException::class);

        $queryBuilder->getQuery();
    }

    public function testEmptyAlias()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();

        $queryBuilder
            ->setEntity('user')
            ->setAttributes([(new Attribute('name'))->setAlias('')])
        ;

        $this->expectException(QueryBuilderException::class);

        $queryBuilder->getQuery();
    }
}
