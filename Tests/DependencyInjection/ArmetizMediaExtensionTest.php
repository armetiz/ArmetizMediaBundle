<?php

namespace Armetiz\MediaBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

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
                "providers" => array (
                    "provider_fake" => array (
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
                            "provider_fake" => array (
                                "formats" => array(
                                    "format_foo" => array (
                                        "generator" => "foo",
                                        "options" => array (
                                            "width" => 512,
                                            "height" => 288,
                                        )
                                    ),
                                    "format_bar" => array (
                                        "generator" => "foo",
                                        "options" => array (
                                            "width" => 512,
                                            "height" => 288,
                                        )
                                    )
                                )
                            )
                        )
                        
                    )
                )
            )
        );
        
        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->hasDefinition('armetiz.media.providers.provider_fake'));
    }
    
    public function testContextDefinition()
    {
        $config = array(
            "armetiz_media" => array (
                "providers" => array (
                    "provider_fake" => array (
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
                            "provider_fake" => array (
                                "formats" => array (
                                    "format_foo" => array (
                                        "generator" => "foo",
                                        "options" => array (
                                            "width" => 512,
                                            "height" => 288,
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
        
        $this->extension->load($config, $this->container);
    }
}
