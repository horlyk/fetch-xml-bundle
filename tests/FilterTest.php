<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Filter;

class FilterTest extends AbstractQueryBuilderTest
{
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
        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="age" operator="gt" value="12"/></filter><all-attributes/></entity></fetch>', $queryBuilder->getQuery());
    }
}
