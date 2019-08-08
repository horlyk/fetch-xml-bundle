<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Attribute;

class AttributeTest extends AbstractQueryBuilderTest
{
    public function testAttributes()
    {
        $queryBuilder = $this->getQueryBuilder();

        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setAttributes([]);
        $this->assertEquals('<fetch><entity name="user"/></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setAttributes(null);
        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $queryBuilder->setAttributes(['firstName', 'lastName']);
        $this->assertEquals(
            '<fetch><entity name="user"><attribute name="firstName" alias="qb_firstName"/><attribute name="lastName" alias="qb_lastName"/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->setAttributeAliasPrefix('test_');
        $this->assertEquals(
            '<fetch><entity name="user"><attribute name="firstName" alias="test_firstName"/><attribute name="lastName" alias="test_lastName"/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->setAttributes(['firstName', (new Attribute('age'))->setAlias('al') , 'lastName']);
        $this->assertEquals(
            '<fetch><entity name="user"><attribute name="firstName" alias="test_firstName"/><attribute name="age" alias="test_al"/><attribute name="lastName" alias="test_lastName"/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->setAttributes([
            'firstName',
            (new Attribute('age'))
                ->setAlias('al')
                ->setAggregate('count')
                ->setDistinct(true),
            'lastName'
        ]);
        $this->assertEquals(
            '<fetch><entity name="user"><attribute name="firstName" alias="test_firstName"/><attribute name="age" alias="test_al" aggregate="count" distinct="true"/><attribute name="lastName" alias="test_lastName"/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->setAttributes([
            (new Attribute('age'))
                ->setDistinct(false),
        ]);
        $this->assertEquals(
            '<fetch><entity name="user"><attribute name="age" alias="test_age" distinct="false"/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder
            ->setAttributes(['firstName', 'lastName'])
            ->setAttributeAliasesAsNames(false);
        $this->assertEquals('<fetch><entity name="user"><attribute name="firstName"/><attribute name="lastName"/></entity></fetch>', $queryBuilder->getQuery());
    }
}
