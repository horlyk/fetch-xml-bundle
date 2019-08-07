<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactory;
use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactoryInterface;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    protected $queryBuilderFactory;

    protected function setUp(): void
    {
        $this->queryBuilderFactory = new QueryBuilderFactory();
    }

    public function testPagination()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();

        $queryBuilder
            ->setEntity('user')
        ;

        $queryBuilder->setUsePager(true);
        $this->assertEquals('<fetch count="10" page="1"><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setItemsPerPage(5);
        $this->assertEquals('<fetch count="5" page="1"><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setCurrentPage(2);
        $this->assertEquals('<fetch count="5" page="2"><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());
    }

    public function testAttributes()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $queryBuilder
            ->setEntity('user')
        ;

        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setAttributes(['firstName', 'lastName']);
        $this->assertEquals('<fetch><entity name="user"><attribute name="firstName" alias="qb_firstName"/><attribute name="lastName" alias="qb_lastName"/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setAttributeAliasPrefix('test_');
        $this->assertEquals('<fetch><entity name="user"><attribute name="firstName" alias="test_firstName"/><attribute name="lastName" alias="test_lastName"/></entity></fetch>', $queryBuilder->getQuery());
    }
}
