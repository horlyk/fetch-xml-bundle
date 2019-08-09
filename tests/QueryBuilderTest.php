<?php

namespace Horlyk\Test;

use DateTime;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Filter;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Relation;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Sort;

class QueryBuilderTest extends AbstractQueryBuilderTest
{
    public function testData()
    {
        $queryBuilder = $this->getQueryBuilder();

        $this->assertEquals([
            'entity' => 'user',
            'itemsPerPage' => 10,
            'currentPage' => 1,
        ], $queryBuilder->getQueryData());
    }

    public function testCountQuery()
    {
        $queryBuilder = $this->getQueryBuilder()
            ->setAttributes(['field_1', 'field_2', 'field_3'])
            ->addFilter(new Filter('field_1', 'value'));

        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="field_1" operator="eq" value="value"/></filter><attribute name="field_1" alias="qb_field_1"/><attribute name="field_2" alias="qb_field_2"/><attribute name="field_3" alias="qb_field_3"/></entity></fetch>',
            $queryBuilder->getQuery());

        $this->assertEquals('<fetch mapping="logical" aggregate="true"><entity name="user"><attribute name="field" alias="count" aggregate="count"/><filter><condition attribute="field_1" operator="eq" value="value"/></filter></entity></fetch>',
            $queryBuilder->getCountQuery('field'));

        $this->assertEquals('<fetch mapping="logical" aggregate="true"><entity name="user"><attribute name="userid" alias="count" aggregate="count"/><filter><condition attribute="field_1" operator="eq" value="value"/></filter></entity></fetch>',
            $queryBuilder->getCountQuery(null));
    }

    public function testDistinctQuery()
    {
        $queryBuilder = $this->getQueryBuilder()
            ->setDistinct(true);

        $this->assertEquals('<fetch distinct="true"><entity name="user"><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());

        $this->assertEquals('<fetch mapping="logical" aggregate="true" distinct="true"><entity name="user"><attribute name="userid" alias="count" aggregate="count"/></entity></fetch>',
            $queryBuilder->getCountQuery());

        $queryBuilder->setDistinct(false);
        $this->assertEquals('<fetch distinct="false"><entity name="user"><all-attributes/></entity></fetch>',
            $queryBuilder->getQuery());
    }

    public function testBuilds()
    {
        $queryBuilder = $this->getQueryBuilder()
            ->addFilter(new Filter('user_id', 999))
            ->setAttributes([])
            ->addRelation(
                (new Relation('company', 'user_id', 'user_id'))
                    ->setIntersect(true)
                    ->addRelation(
                        (new Relation('address', 'address_id', 'address_id'))
                            ->setAttributes(['city', 'zip'])
                            ->addRelation(
                                (new Relation('friend', 'address_id', 'friend_id'))
                                    ->setIntersect(true)
                                    ->addRelation(
                                        (new Relation('user', 'user_id', 'user_id'))
                                            ->setAttributes(['lastname', 'firstname', 'email', 'user_id'])
                                            ->addRelation(
                                                (new Relation('phone', 'user_id', 'user'))
                                                    ->addFilter(new Filter('type', 'mobile', 'neq'))
                                            )
                                    )
                            )
                    )
            );

        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="user_id" operator="eq" value="999"/></filter><link-entity name="company" to="user_id" from="user_id" intersect="true"><link-entity name="address" to="address_id" from="address_id"><attribute name="city" alias="qb_city"/><attribute name="zip" alias="qb_zip"/><link-entity name="friend" to="address_id" from="friend_id" intersect="true"><link-entity name="user" to="user_id" from="user_id"><attribute name="lastname" alias="qb_lastname"/><attribute name="firstname" alias="qb_firstname"/><attribute name="email" alias="qb_email"/><attribute name="user_id" alias="qb_user_id"/><link-entity name="phone" to="user_id" from="user"><filter><condition attribute="type" operator="neq" value="mobile"/></filter></link-entity></link-entity></link-entity></link-entity></link-entity></entity></fetch>',
            $queryBuilder->getQuery());

        $startDate = (new DateTime())->format('Y-m-d H:i');
        $queryBuilder = $this->getQueryBuilder()
            ->setEntity('type')
            ->setAttributes(['field_1', 'field_2', 'field_3'])
            ->setCurrentPage(2)
            ->setUsePager(true)
            ->addSortOrder(new Sort('startDate', 'desc'))
            ->addFilter(
                new Filter('startDate', $startDate, 'le')
            );

        $this->assertEquals('<fetch count="10" page="2"><entity name="type"><order attribute="startDate" descending="true"/><filter><condition attribute="startDate" operator="le" value="'.$startDate.'"/></filter><attribute name="field_1" alias="qb_field_1"/><attribute name="field_2" alias="qb_field_2"/><attribute name="field_3" alias="qb_field_3"/></entity></fetch>',
            $queryBuilder->getQuery());

        $queryBuilder = $this->getQueryBuilder()
            ->setEntity('phone')
            ->setAttributes(['type', 'label', 'value'])
            ->addFilter(new Filter('phone_id', '777'))
            ->addRelation(
                (new Relation())
                    ->setEntityName('user')
                    ->setCurrentEntityField('user_id')
                    ->setRelationEntityField('user')
                    ->setAttributes([
                        'lastname', 'firstname', 'status',
                    ])
                    ->addSortOrder(new Sort('firstname', 'desc')
                    )
            )
            ->addRelation(
                (new Relation())
                    ->setEntityName('type')
                    ->setCurrentEntityField('phone')
                    ->setRelationEntityField('phone_id')
                    ->setAttributes(['label', 'description'])
                    ->setJoinType('outer')
            );
        $this->assertEquals('<fetch><entity name="phone"><filter><condition attribute="phone_id" operator="eq" value="777"/></filter><link-entity name="user" to="user" from="user_id"><attribute name="lastname" alias="qb_lastname"/><attribute name="firstname" alias="qb_firstname"/><attribute name="status" alias="qb_status"/><order attribute="firstname" descending="true"/></link-entity><link-entity name="type" to="phone_id" from="phone" link-type="outer"><attribute name="label" alias="qb_label"/><attribute name="description" alias="qb_description"/></link-entity><attribute name="type" alias="qb_type"/><attribute name="label" alias="qb_label"/><attribute name="value" alias="qb_value"/></entity></fetch>',
            $queryBuilder->getQuery());

        $queryBuilder = $this->getQueryBuilder()
            ->addFilter(new Filter('user_id', 998))
            ->setItemsPerPage(1)
            ->setAttributes([])
            ->addRelation(
                (new Relation('phone', 'user_id', 'user_id'))
                    ->setJoinType('inner')
                    ->setIntersect(true)
                    ->addRelation(
                        (new Relation('type', 'user_id', 'user'))
                            ->addFilter(new Filter('type_id', 123))
                            ->setAttributes([
                                'field_1',
                                'field_2',
                                'field_3',
                            ])
                            ->addRelation(
                                (new Relation('user', 'user_id'))
                                    ->setJoinType('outer')
                                    ->setAttributes([
                                        'firstName',
                                        'lastName',
                                        'email',
                                    ])
                            )
                    )
            );

        $this->assertEquals('<fetch><entity name="user"><filter><condition attribute="user_id" operator="eq" value="998"/></filter><link-entity name="phone" to="user_id" from="user_id" link-type="inner" intersect="true"><link-entity name="type" to="user_id" from="user"><attribute name="field_1" alias="qb_field_1"/><attribute name="field_2" alias="qb_field_2"/><attribute name="field_3" alias="qb_field_3"/><filter><condition attribute="type_id" operator="eq" value="123"/></filter><link-entity name="user" to="user_id" link-type="outer"><attribute name="firstName" alias="qb_firstName"/><attribute name="lastName" alias="qb_lastName"/><attribute name="email" alias="qb_email"/></link-entity></link-entity></link-entity></entity></fetch>',
            $queryBuilder->getQuery());
    }
}

