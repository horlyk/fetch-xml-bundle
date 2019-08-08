Examples
============

Select 'first_name' and 'last_name' from contact entity whose email is 'example@gmail.com' :
```php

$contactQueryBuilder = ($queryBuilderFactory->createBuilder())
            ->setEntity('contact')
            ->setAttributes([
                'first_name',
                'last_name',
            ])
            ->addFilter(new Filter('email', 'example@gmail.com'));

$query = $contactQueryBuilder->getQuery();
$query = $contactQueryBuilder->getCountQuery();
```

Example with relations and sorting :
```php
$contactQueryBuilder = $queryBuilderFactory->createBuilder();
        
$contactQueryBuilder
    ->setEntity('entity_name')
    ->setAttributes(['field_id'])
    ->addFilter(new Filter('field_1', $fieldValue))
    ->addSortOrder((new Sort('field_3')))
    ->addRelation(
        (new Relation('entity_name', 'to', 'from'))
            ->setAttributes([
                'field_1', 'field_2', 'field_3',
            ])
            ->addSortOrder((new Sort('field_2', 'desc')))
            ->addFilter(new Filter('field_3', $fieldValue))
    )
    ->addRelation(
        (new Relation('entity_name', 'to', 'from'))
            ->setAttributes(['field_1', 'field_2'])
            ->setJoinType('outer')
            ->addRelation(
                (new Relation('entity_name', 'to', 'from'))
            )
    );
```

Documentation navigation
-------------

* [Readme](/README.md)
* [Getting started](/src/Resources/doc/index.md)
* [Configuration](/src/Resources/doc/configuration.md)
