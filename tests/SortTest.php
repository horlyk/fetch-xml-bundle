<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Sort;

class SortTest extends AbstractQueryBuilderTest
{
    public function testSort()
    {
        $queryBuilder = $this->getQueryBuilder();

        $data = [
            new Sort('name'),
            new Sort('age', 'asc'),
            new Sort('salary', 'desc')
        ];

        $queryBuilder->addSortOrder($data[0]);
        $this->assertEquals('<fetch><entity name="user"><order attribute="name" descending="false"/><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->addSortOrder($data[1]);
        $this->assertEquals('<fetch><entity name="user"><order attribute="name" descending="false"/><order attribute="age" descending="false"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());

        $queryBuilder->addSortOrder($data[2]);
        $this->assertEquals('<fetch><entity name="user"><order attribute="name" descending="false"/><order attribute="age" descending="false"/><order attribute="salary" descending="true"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());

        $this->assertEquals($data, $queryBuilder->getSortOrders());

        $queryBuilder->setSortOrders([]);
        $this->assertEquals([], $queryBuilder->getSortOrders());

        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());
    }
}
