<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Filter;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Relation;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Sort;

class RelationTest extends AbstractQueryBuilderTest
{
    public function testRelation()
    {
        $queryBuilder = $this->getQueryBuilder();

        /** @var Relation[] $data */
        $data = [
            (new Relation())
                ->setEntityName('phone')
                ->setRelationEntityField('user'),
            (new Relation('company'))
                ->setRelationEntityField('user'),
            (new Relation('category', 'user'))
                ->setCurrentEntityField('category_id'),
            (new Relation('address', 'user', 'user_id')),
        ];

        $queryBuilder->addRelation($data[0]);
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="phone" to="user"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->addRelation($data[1]);
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="phone" to="user"/><link-entity name="company" to="user"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->addRelation($data[2]);
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="phone" to="user"/><link-entity name="company" to="user"/><link-entity name="category" to="user" from="category_id"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->addRelation($data[3]);
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="phone" to="user"/><link-entity name="company" to="user"/><link-entity name="category" to="user" from="category_id"/><link-entity name="address" to="user" from="user_id"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $this->assertEquals($data, $queryBuilder->getRelations());

        $queryBuilder->setRelations([]);
        $this->assertEquals([], $queryBuilder->getRelations());

        $this->assertEquals('<fetch><entity name="user"><all-attributes/></entity></fetch>', $queryBuilder->getQuery());

        $relation = (new Relation('address', 'user', 'user_id'))
            ->setJoinType('outer')
            ->setIntersect(true);

        $queryBuilder->addRelation($relation);
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id" link-type="outer" intersect="true"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $relation->setIntersect(false);
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id" link-type="outer" intersect="false"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $queryBuilder->setRelations([]);
    }

    public function testRelationFilters()
    {
        $queryBuilder = $this->getQueryBuilder();

        $relation = (new Relation('address', 'user', 'user_id'));
        $queryBuilder->addRelation($relation);

        $relation->addFilter(new Filter('city', 'Babruysk'));

        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"><filter><condition attribute="city" operator="eq" value="Babruysk"/></filter></link-entity><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $relation->addFilter(new Filter('country', 'Belarus'));
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"><filter><condition attribute="city" operator="eq" value="Babruysk"/><condition attribute="country" operator="eq" value="Belarus"/></filter></link-entity><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $relation->setFilters([]);

        $this->assertEquals([], $relation->getFilters());
    }

    public function testRelationSorts()
    {
        $queryBuilder = $this->getQueryBuilder();

        $relation = (new Relation('address', 'user', 'user_id'));
        $queryBuilder->addRelation($relation);

        $relation->addSortOrder(new Sort('city'));

        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"><order attribute="city" descending="false"/></link-entity><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $relation->addSortOrder(new Sort('zip', 'desc'));
        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"><order attribute="city" descending="false"/><order attribute="zip" descending="true"/></link-entity><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $relation->setSorts([]);

        $this->assertEquals([], $relation->getSorts());
    }

    public function testRelationRelations()
    {
        $queryBuilder = $this->getQueryBuilder();

        $relation = (new Relation('address', 'user', 'user_id'));

        $queryBuilder->addRelation($relation);

        $relationType = (new Relation('type', 'address', 'address_id'));

        $relation->addRelation($relationType);

        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"><link-entity name="type" to="address" from="address_id"/></link-entity><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );

        $relation->setRelations([]);
        $this->assertEquals([], $relation->getRelations());

        $this->assertEquals(
            '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"/><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery()
        );
    }

    /**
     * Relation with attributes=[] should render no attributes
     *
     * @throws \Horlyk\Bundle\FetchXmlBundle\Exception\QueryBuilderException
     */
    public function testRelationNoAttributes()
    {
        $queryBuilder = $this->getQueryBuilder();
        $relation = (new Relation('address', 'user', 'user_id'));
        $relation->setAttributes([]);
        $queryBuilder->addRelation($relation);

        $expected = '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"/><all-attributes/></entity></fetch>';
        $this->assertEquals($expected, $queryBuilder->getQuery());
    }


    /**
     * Relation with attributes=null should render a all-attributes node
     *
     * @throws \Horlyk\Bundle\FetchXmlBundle\Exception\QueryBuilderException
     */
    public function testRelationAllAttributes()
    {
        $queryBuilder = $this->getQueryBuilder();
        $relation = (new Relation('address', 'user', 'user_id'));
        $relation->setAttributes(null);
        $queryBuilder->addRelation($relation);

        $expected = '<fetch><entity name="user"><link-entity name="address" to="user" from="user_id"><all-attributes/></link-entity><all-attributes/></entity></fetch>';
        $this->assertEquals($expected, $queryBuilder->getQuery());
    }
}
