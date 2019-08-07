Configuration
============

This bundle provides following configuration options:
```yaml
horlyk_fetch_xml:
    use_pager: false
    items_per_page: 10
    attribute_aliases_as_names: true
    attribute_alias_prefix: 'ep_'
```

* ```use_pager``` - allows you to build paginated output.

* ```items_per_page``` - if the "use_pager" option is enabled, this allows you to set items per page.

* ```attribute_aliases_as_names``` - this option is used to set attribute alias same as a field name.
This can be useful when you have a lot of relations where the needed field name is something like 
`contact4.field_name` and can vary from one query to another, like `contact5.field_name`. It is used
 along with an `attribute_alias_prefix` option.

* ```attribute_alias_prefix``` - applies given prefix to all aliases.

Documentation navigation
-------------

* [Readme](src/README.md)
* [Getting started](src/Resources/doc/index.md)
* [Examples](src/Resources/doc/examples.md)
