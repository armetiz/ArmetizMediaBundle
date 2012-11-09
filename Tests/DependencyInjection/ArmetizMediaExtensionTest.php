<?php

namespace Armetiz\MediaBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

use Armetiz\MediaBundle\DependencyInjection\ArmetizMediaExtension;

class ArmetizMediaExtensionTest extends \PHPUnit_Framework_TestCase {
    private $container;
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new ArmetizMediaExtension();
    }

    public function tearDown()
    {
        unset($this->container, $this->extension);
    }
    
    public function testProviderDefinition()
    {
        $config = array(
            "armetiz_media" => array (
                "cdns" => array (
                    "local" => array ("base_url" => "http://localhost/medias/")
                ),
                "providers" => array (
                    "provider_fake" => array (
                        "cdn" => "local",
                        "templates" => array (
                            "default" => "FakeTemplate",
                            "foo" => "FooTemplate"
                        )
                    )
                ),
                "contexts" => array (
                    "fake" => array (
                        "managed" => array (
                            "FakeMedia",
                            "FooMedia",
                            "BarMedia"
                        ),
                        "providers" => array (
                            "provider_fake"
                        )
                        
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->hasDefinition('armetiz.media.providers.provider_fake'));
    }
}
