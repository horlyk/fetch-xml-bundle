<?php

namespace Horlyk\Test;

use Horlyk\Bundle\FetchXmlBundle\Exception\QueryBuilderException;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Attribute;
use InvalidArgumentException;
use stdClass;

class ExceptionTest extends AbstractQueryBuilderTest
{
    public function testEntityNameNotGiven()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $expectedErrorMessage = 'Entity name was not provided.';
        $errorMessage = '';

        try {
            $queryBuilder->getQuery();
        } catch (QueryBuilderException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    public function testEmptyAliasPrefix()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $expectedErrorMessage = 'It is not allowed to use empty attribute alias prefix with attribute_aliases_as_names option set to true';
        $errorMessage = '';

        $queryBuilder
            ->setEntity('user')
            ->setAttributes([])
            ->setAttributeAliasPrefix('');

        try {
            $queryBuilder->getQuery();
        } catch (QueryBuilderException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    public function testEmptyAlias()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $expectedErrorMessage = 'Alias value must be a string.';
        $errorMessage = '';

        $queryBuilder
            ->setEntity('user')
            ->setAttributes([(new Attribute('name'))->setAlias('')])
        ;

        try {
            $queryBuilder->getQuery();
        } catch (QueryBuilderException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    public function testAggregateValue()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $expectedErrorMessage = 'Aggregate value must be a string.';
        $errorMessage = '';

        $queryBuilder
            ->setEntity('user')
            ->setAttributes([(new Attribute('name'))->setAggregate('')])
        ;

        try {
            $queryBuilder->getQuery();
        } catch (QueryBuilderException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    public function testCountQueryThrowsException()
    {
        $queryBuilder = $this->queryBuilderFactory->createBuilder();
        $expectedErrorMessage = '$attribute parameter should be a string or an Attribute object.';
        $errorMessage = '';

        $queryBuilder
            ->setEntity('user')
            ->setAttributes([(new Attribute('name'))->setAggregate('')])
        ;

        try {
            $queryBuilder->getCountQuery(new stdClass());
        } catch (InvalidArgumentException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }
}
