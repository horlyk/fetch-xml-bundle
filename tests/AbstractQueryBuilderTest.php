<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactory;
use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactoryInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractQueryBuilderTest extends TestCase
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    protected $queryBuilderFactory;

    protected function setUp(): void
    {
        $this->queryBuilderFactory = new QueryBuilderFactory();
    }

    protected function getQueryBuilder()
    {
        return $this->queryBuilderFactory->createBuilder()
            ->setEntity('user')
        ;
    }
}
