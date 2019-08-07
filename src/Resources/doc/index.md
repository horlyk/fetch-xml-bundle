Getting started
===============
### Usage

You can use builder directly:

```php
public function helloAction(QueryBuilderInterface $queryBuilder)
{
    
    $query = $newQueryBuilder
                ->setEntity('entity_name')
                ->getQuery();
}
```

...or you can use a Factory to create as many query builders as you wish:

```php
public function helloAction(QueryBuilderFactoryInterface $queryBuilderFactory)
{
    $newQueryBuilder = $queryBuilderFactory->createBuilder();
    
    $query = $newQueryBuilder
                ->setEntity('entity_name')
                ->getQuery();
}
```

Documentation navigation
-------------

* [Readme](src/README.md)
* [Configuration](src/Resources/doc/configuration.md)
* [Examples](src/Resources/doc/examples.md)
