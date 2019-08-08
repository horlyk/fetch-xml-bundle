<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilderObject;

class Sort
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $direction;

    public function __construct(string $field, ?string $direction = 'asc')
    {
        $this->setField($field);
        $this->setDirection($direction ?: 'asc');
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }
}
