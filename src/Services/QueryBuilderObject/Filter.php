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
     * @var string|array
     */
    private $value;

    /**
     * @var string|null
     *
     * ToDo allow adding filter type
     */
    private $type;

    /**
     * @param string|array $value
     */
    public function __construct(string $attribute, $value, ?string $operator = null)
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

    /**
     * @return array|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array|string $value
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
