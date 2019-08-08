<?php

namespace Horlyk\Test;

class PaginationTest extends AbstractQueryBuilderTest
{
    public function testPagination()
    {
        $queryBuilder = $this->getQueryBuilder();

        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setUsePager(true);
        $this->assertEquals('<fetch count="10" page="1"><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setItemsPerPage(5);
        $this->assertEquals('<fetch count="5" page="1"><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setCurrentPage(2);
        $this->assertEquals('<fetch count="5" page="2"><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());
    }
}
