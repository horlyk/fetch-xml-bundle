<?php

namespace Horlyk\Test\DependencyInjection;

use Horlyk\Bundle\FetchXmlBundle\DependencyInjection\HorlykFetchXmlExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HorlykFetchXmlExtensionTest extends TestCase
{
    public function testLoad() {
        $containerBuilder = new ContainerBuilder();
        $ext = new HorlykFetchXmlExtension();

        $ext->load([], $containerBuilder);

        $this->assertFalse($containerBuilder->getParameter('horlyk_fetch_xml.use_pager'));
        $this->assertEquals(10, $containerBuilder->getParameter('horlyk_fetch_xml.items_per_page'));
        $this->assertTrue($containerBuilder->getParameter('horlyk_fetch_xml.attribute_aliases_as_names'));
        $this->assertEquals('qb_', $containerBuilder->getParameter('horlyk_fetch_xml.attribute_alias_prefix'));
    }
}
