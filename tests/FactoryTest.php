<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactory;
use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactoryInterface;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilder;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    protected $queryBuilderFactory;

    protected function setUp(): void
    {
        $this->queryBuilderFactory = new QueryBuilderFactory();
    }

    public function testFactory()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }
}
