<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services;

use Horlyk\Bundle\FetchXmlBundle\Exception\QueryBuilderException;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Attribute;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Filter;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Relation;
use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject\Sort;
use function count;
use DOMDocument;
use DOMElement;
use DOMNode;
use InvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var DOMDocument
     */
    private $queryDom;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var bool
     */
    private $usePager;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $itemsPerPage;

    /**
     * @var bool
     */
    private $attributeAliasesAsNames;

    /**
     * @var string
     */
    private $attributeAliasPrefix;

    /**
     * @var array|Attribute[]|null
     */
    private $attributes = null;

    /**
     * @var array|Filter[]
     */
    private $filters = [];

    /**
     * @var array|Relation[]
     */
    private $relations = [];

    /**
     * @var array|Sort[]
     */
    private $sorts = [];

    public function __construct()
    {
        $this->queryDom = new DOMDocument();
    }

    public function getQueryData(): array
    {
        return [
            'entity' => $this->entity,
            'itemsPerPage' => $this->itemsPerPage,
            'currentPage' => $this->currentPage,
        ];
    }

    public function getQuery(): string
    {
        $fetchParameters = [];

        if (!$this->entity) {
            throw new QueryBuilderException('Entity name was not provided.');
        }

        if ($this->usePager) {
            $fetchParameters = [
                'count' => $this->itemsPerPage,
                'page' => $this->currentPage,
            ];
        }

        $fetch = $this->queryDom->appendChild($this->createDomElement('fetch', $fetchParameters));

        $entity = $fetch->appendChild($this->createDomElement('entity', [
            'name' => $this->entity,
        ]));

        $this->applySortOrders($this->sorts, $entity);

        $this->applyCommon($entity);
        $this->applyAttributes($this->attributes, $entity);

        return $this->queryDom->saveXML($fetch);
    }

    /**
     * @param string|Attribute|null $attribute
     */
    public function getCountQuery($attribute = null): string
    {
        $fetch = $this->queryDom->appendChild($this->createDomElement('fetch', [
            'mapping' => 'logical',
            'aggregate' => 'true',
        ]));

        $entity = $fetch->appendChild($this->createDomElement('entity', [
            'name' => $this->entity,
        ]));

        if (null === $attribute) {
            $attribute = (new Attribute("{$this->entity}id"))
                ->setAggregate('count')
                ->setAlias('count')
            ;
        }else if (is_string($attribute)) {
            $attribute = (new Attribute($attribute))
                ->setAggregate('count')
                ->setAlias('count')
            ;
        } else if (!$attribute instanceof Attribute) {
            throw new InvalidArgumentException('$attribute parameter should be a string or an Attribute object.');
        }

        $this->applyAttribute($attribute, $entity);

        $this->applyCommon($entity);

        return $this->queryDom->saveXML($fetch);
    }

    private function applyCommon($entity): void
    {
        $this->applyFilters($this->filters, $entity);
        $this->applyRelations($this->relations, $entity);
    }

    private function applyFilters(array $filters, DOMNode $entity): void
    {
        if (!count($filters)) {
            return;
        }

        $filterEntity = $entity->appendChild($this->createDomElement('filter'));

        foreach ($filters as $filter) {
            $this->applyFilter($filter, $filterEntity);
        }
    }

    private function applyFilter(Filter $filter, DOMNode $element): void
    {
        $filterData = [
            'attribute' => $filter->getAttribute(),
            'operator' => $filter->getOperator(),
        ];

        if (!is_array($filter->getValue())) {
            $filterData['value'] = $filter->getValue();
        }

        $condition = $element->appendChild($this->createDomElement('condition', $filterData));

        if (is_array($filter->getValue())) {
            foreach ($filter->getValue() as $item) {
                $value = $condition->appendChild($this->createDomElement('value'));
                $value->nodeValue = $item;
            }
        }
    }

    private function applyRelations(array $relations, DOMNode $entity): void
    {
        foreach ($relations as $relation) {
            $this->applyRelation($relation, $entity);
        }
    }

    private function applyRelation(Relation $relation, DOMNode $element): void
    {
        $joinParameters = [
            'name' => $relation->getEntityName(),
            'to' => $relation->getRelationEntityField(),
        ];

        if ($relation->getCurrentEntityField()) {
            $joinParameters['from'] = $relation->getCurrentEntityField();
        }

        if (null !== $relation->getJoinType()) {
            $joinParameters['link-type'] = $relation->getJoinType();
        }

        if (null !== $relation->getIntersect()) {
            $joinParameters['intersect'] = $this->boolToString($relation->getIntersect());
        }

        $linkedRelation = $element->appendChild($this->createDomElement('link-entity', $joinParameters));

        $this->applyAttributes($relation->getAttributes(), $linkedRelation);
        $this->applyFilters($relation->getFilters(), $linkedRelation);
        $this->applySortOrders($relation->getSorts(), $linkedRelation);
        $this->applyRelations($relation->getRelations(), $linkedRelation);
    }

    private function applyAttributes(?array $attributes, DOMNode $entity): void
    {
        if (null === $attributes) {
            $entity->appendChild($this->queryDom->createElement('all-attributes'));

            return;
        }

        if ($this->attributeAliasesAsNames && !$this->attributeAliasPrefix) {
            throw new QueryBuilderException('It is not allowed to use empty attribute alias prefix with attribute_aliases_as_names option set to true');
        }

        foreach ($attributes as $attribute) {
            $attributeObject = is_string($attribute) ? new Attribute($attribute) : $attribute;

            if (null === $attributeObject->getAlias() && $this->attributeAliasesAsNames) {
                $attributeObject->setAlias($this->attributeAliasPrefix.$attributeObject->getName());
            } else if ($attributeObject->getAlias() && $this->attributeAliasesAsNames) {
                $attributeObject->setAlias($this->attributeAliasPrefix.$attributeObject->getAlias());
            }

            $this->applyAttribute($attributeObject, $entity);
        }
    }

    private function applyAttribute(Attribute $attribute, DOMNode $entity): void
    {
        $parameters = [];
        $parameters['name'] = $attribute->getName();

        if (null !== $attribute->getAlias()) {
            if (!$attribute->getAlias()) {
                throw new QueryBuilderException('Alias value must be a string.');
            }

            $parameters['alias'] = $attribute->getAlias();
        }

        if (null !== $attribute->getAggregate()) {
            if (!$attribute->getAggregate()) {
                throw new QueryBuilderException('Aggregate value must be a string.');
            }
            $parameters['aggregate'] = $attribute->getAggregate();
        }

        if (null !== $attribute->getDistinct()) {
            $parameters['distinct'] = $this->boolToString($attribute->getDistinct());
        }

        $entity->appendChild($this->createDomElement('attribute', $parameters));
    }

    private function applySortOrders(array $sortOrders, DOMNode $element): void
    {
        foreach ($sortOrders as $sortOrder) {
            $this->applySortOrder($sortOrder, $element);
        }
    }

    private function applySortOrder(Sort $sortOrder, DOMNode $element): void
    {
        $element->appendChild($this->createDomElement('order', [
            'attribute' => $sortOrder->getField(),
            'descending' => $this->boolToString('desc' === $sortOrder->getDirection()),
        ]));
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(?array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function setCurrentPage(int $page): self
    {
        $this->currentPage = $page;

        return $this;
    }

    public function setItemsPerPage(int $itemsCount): self
    {
        $this->itemsPerPage = $itemsCount;

        return $this;
    }

    public function setUsePager(bool $usePager): self
    {
        $this->usePager = $usePager;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function addFilter(Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function setRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }

    public function addRelation(Relation $relation): self
    {
        $this->relations[] = $relation;

        return $this;
    }

    public function getSortOrders(): array
    {
        return $this->sorts;
    }

    public function setSortOrders(array $sorts): self
    {
        $this->sorts = $sorts;

        return $this;
    }

    public function addSortOrder(Sort $sortOrder): self
    {
        $this->sorts[] = $sortOrder;

        return $this;
    }

    private function createDomElement(string $elementName, ?array $attributes = []): DOMElement
    {
        $element = $this->queryDom->createElement($elementName);

        foreach ($attributes as $attributeName => $value) {
            $element->setAttribute($attributeName, $value);
        }

        return $element;
    }

    public function setAttributeAliasesAsNames(bool $attributeAliasesAsNames): self
    {
        $this->attributeAliasesAsNames = $attributeAliasesAsNames;

        return $this;
    }

    public function setAttributeAliasPrefix(string $attributeAliasPrefix): self
    {
        $this->attributeAliasPrefix = $attributeAliasPrefix;

        return $this;
    }

    private function boolToString(bool $value)
    {
        return $value ? 'true' : 'false';
    }
}
