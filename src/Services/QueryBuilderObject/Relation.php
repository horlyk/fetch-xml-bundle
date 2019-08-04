<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject;

class Relation
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $currentEntityField;

    /**
     * @var string
     */
    private $relationEntityField;

    /**
     * @var string|null
     */
    private $joinType;

    /**
     * @var string|null
     */
    private $intersect;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array|Sort[]
     */
    private $sorts = [];

    /**
     * @var array|Relation[]
     */
    private $relations = [];

    /**
     * @var array|Filter[]
     */
    private $filters = [];

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityName): self
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function getCurrentEntityField(): string
    {
        return $this->currentEntityField;
    }

    public function setCurrentEntityField(string $currentEntityField): self
    {
        $this->currentEntityField = $currentEntityField;

        return $this;
    }

    public function getRelationEntityField(): string
    {
        return $this->relationEntityField;
    }

    public function setRelationEntityField(string $relationEntityField): self
    {
        $this->relationEntityField = $relationEntityField;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getSorts(): array
    {
        return $this->sorts;
    }

    public function setSorts(array $sorts): self
    {
        $this->sorts = $sorts;

        return $this;
    }

    public function addSortOrder(Sort $sortOrder): self
    {
        $this->sorts[] = $sortOrder;

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

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function addFilter(Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getJoinType(): ?string
    {
        return $this->joinType;
    }

    public function setJoinType(string $joinType): self
    {
        $this->joinType = $joinType;

        return $this;
    }

    public function getIntersect(): ?string
    {
        return $this->intersect;
    }

    public function setIntersect(string $intersect): self
    {
        $this->intersect = $intersect;

        return $this;
    }
}
