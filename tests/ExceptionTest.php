<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Exception\QueryBuilderException;
use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactory;
use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactoryInterface;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    protected $queryBuilderFactory;

    protected function setUp(): void
    {
        $this->queryBuilderFactory = new QueryBuilderFactory();
    }

    public function testEntityNameNotGiven()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();

        $this->expectException(QueryBuilderException::class);

        $queryBuilder->getQuery();
    }
}
