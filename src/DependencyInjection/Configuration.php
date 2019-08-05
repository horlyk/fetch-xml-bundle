<?php

namespace Horlyk\Bundle\FetchXmlBundle\DependencyInjection;

use Horlyk\Bundle\FetchXmlBundle\Services\Factory\QueryBuilderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('horlyk_fetchxml');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('use_pager')
                    ->defaultValue(QueryBuilderFactoryInterface::DEFAULT_USE_PAGER)
                ->end()
                ->integerNode('items_per_page')
                    ->defaultValue(QueryBuilderFactoryInterface::DEFAULT_ITEMS_PER_PAGE)
                ->end()
                ->booleanNode('attribute_aliases_as_names')
                    ->defaultValue(QueryBuilderFactoryInterface::DEFAULT_ATTRIBUTE_ALIASES_AS_NAMES)
                ->end()
                ->scalarNode('attribute_alias_prefix')
                    ->defaultValue(QueryBuilderFactoryInterface::DEFAULT_ATTRIBUTE_ALIAS_PREFIX)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
