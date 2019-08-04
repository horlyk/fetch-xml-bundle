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

    public function setAttribute(string $attribute): void
    {
        $this->attribute = $attribute;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
