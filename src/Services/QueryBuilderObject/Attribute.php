<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject;

class Attribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * @var string|null
     */
    private $aggregate;

    /**
     * @var bool|null
     */
    private $distinct;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAggregate(): ?string
    {
        return $this->aggregate;
    }

    public function setAggregate(?string $aggregate): self
    {
        $this->aggregate = $aggregate;

        return $this;
    }

    public function getDistinct(): ?bool
    {
        return $this->distinct;
    }

    public function setDistinct(?bool $distinct): self
    {
        $this->distinct = $distinct;

        return $this;
    }
}
