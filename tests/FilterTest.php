<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Filter;

class FilterTest extends AbstractQueryBuilderTest
{
    public function testFilterEmptyValue()
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->addFilter(new Filter('name','','ne'));

        $expected = '<fetch><entity name="user"><condition attribute="name" operator="ne" value=""/><all-attributes/></entity></fetch>';
        $this->assertEquals($expected, $queryBuilder->getQuery());
    }

    public function testFilter()
    {
        $queryBuilder = $this->getQueryBuilder();

        $data = [
            new Filter('name', 'Bob'),
            new Filter('age', '18', 'ne'),
        ];

        $queryBuilder->addFilter($data[0]);
        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="name" operator="eq" value="Bob"/></filter><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->addFilter($data[1]);
        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="name" operator="eq" value="Bob"/><condition attribute="age" operator="ne" value="18"/></filter><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());

        $this->assertEquals($data, $queryBuilder->getFilters());

        $queryBuilder->setFilters([]);
        $this->assertEquals([], $queryBuilder->getFilters());

        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $filter = (new Filter('name', 'Bob'))
            ->setAttribute('age')
            ->setOperator('gt')
            ->setValue(12);

        $queryBuilder->addFilter($filter);
        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="age" operator="gt" value="12"/></filter><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());

        $queryBuilder->setFilters([]);
        $filter = new Filter('name', ['Bob', 'John'], 'in');

        $queryBuilder->addFilter($filter);

        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="name" operator="in"><value>Bob</value><value>John</value></condition></filter><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());

        $filter->setType('or');

        $this->assertEquals('or', $filter->getType());

        $filter
            ->setEntityName('testEntityName')
            ->setValue(null)
            ->setAttribute('testEntityAttribute')
            ->setOperator("null");

        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="testEntityAttribute" operator="null" entityname="testEntityName"/></filter><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());
    }
}
