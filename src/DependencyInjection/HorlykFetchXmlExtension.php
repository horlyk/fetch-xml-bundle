<?php

namespace Horlyk\Bundle\FetchXmlBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class HorlykFetchXmlExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $container->setParameter('horlyk_fetchxml.use_pager', $config['use_pager']);
        $container->setParameter('horlyk_fetchxml.items_per_page', $config['items_per_page']);
        $container->setParameter('horlyk_fetchxml.attribute_aliases_as_names', $config['attribute_aliases_as_names']);
        $container->setParameter('horlyk_fetchxml.attribute_alias_prefix', $config['attribute_alias_prefix']);
    }
}
