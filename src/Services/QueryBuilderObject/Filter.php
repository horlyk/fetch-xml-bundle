<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject;

class Filter
{
    /**
     * @var string
     */
    private $attribute;
    /**
     * @var string
     */
    private $operator = 'eq';
    /**
     * @var string
     */
    private $value;

    public function __construct(string $attribute, string $value, ?string $operator = null)
    {
        $this->attribute = $attribute;
        $this->value = $value;
        $this->operator = $operator ?? $this->operator;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
