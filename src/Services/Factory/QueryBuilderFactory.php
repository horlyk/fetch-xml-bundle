<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services\Factory;

use Horlyk\Bundle\FetchXmlBundle\Services\QueryBuilder;

class QueryBuilderFactory implements QueryBuilderFactoryInterface
{
    /**
     * @var bool|null
     */
    private $usePager;
    /**
     * @var int|null
     */
    private $itemsPerPage;
    /**
     * @var bool|null
     */
    private $attributeAliasesAsNames;
    /**
     * @var string|null
     */
    private $attributeAliasPrefix;

    public function __construct(?bool $usePager = self::DEFAULT_USE_PAGER, ?int $itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE, ?bool $attributeAliasesAsNames = self::DEFAULT_ATTRIBUTE_ALIASES_AS_NAMES, ?string $attributeAliasPrefix = self::DEFAULT_ATTRIBUTE_ALIAS_PREFIX)
    {
        $this->usePager = $usePager ?? false;
        $this->itemsPerPage = $itemsPerPage ?? 10;
        $this->attributeAliasesAsNames = $attributeAliasesAsNames ?? true;
        $this->attributeAliasPrefix = $attributeAliasPrefix ?? '';
    }

    public function createBuilder(): QueryBuilder
    {
        $queryBuilder = (new QueryBuilder())
            ->setUsePager($this->usePager)
            ->setItemsPerPage($this->itemsPerPage)
            ->setCurrentPage(1)
            ->setAttributeAliasesAsNames($this->attributeAliasesAsNames)
            ->setAttributeAliasPrefix($this->attributeAliasPrefix)
        ;

        return $queryBuilder;
    }
}
