<?php

namespace Armetiz\MediaBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;

use Armetiz\MediaBundle\DependencyInjection\Configuration;

class ConfigurationText extends \PHPUnit_Framework_TestCase {
    private $configuration;
    private $processor;

    public function setUp()
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function tearDown()
    {
        unset($this->configuration, $this->processor);
    }
    
    public function testDefinition()
    {
        $configRaw = array(
            "armetiz_media" => array (
                "contexts" => array (
                    "context_foo" => array (
                        "managed" => array (
                            "FakeMedia",
                            "FooMedia",
                            "BarMedia"
                        ),
                        "formats" => array (
                            "format_foo" => array(),
                            "format_bar" => array(),
                        ),
                        "providers" => array (
                            "provider_foo" => array (
                                "formats" => array (
                                    "format_foo" => array (
                                        "transformer" => "foo",
                                        "options" => array (
                                            "height" => "auto",
                                            "width" => 512,
                                        )
                                    )
                                )
                            ),
                            "provider_bar" => array (
                                "formats" => array (
                                    "format_foo" => array (
                                        "transformer" => "foo",
                                        "options" => array (
                                            "height" => "auto",
                                            "width" => 512,
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
        
        $config = $this->processor->processConfiguration($this->configuration, $configRaw);
        
        $this->assertArrayHasKey("contexts", $config);
        $this->assertArrayHasKey("context_foo", $config["contexts"]);
        $this->assertArrayHasKey("managed", $config["contexts"]["context_foo"]);
        $this->assertArrayHasKey("providers", $config["contexts"]["context_foo"]);
        
        $this->assertArrayHasKey("providers", $config["contexts"]["context_foo"]);
        $this->assertContains("FakeMedia", $config["contexts"]["context_foo"]["managed"]);
        $this->assertContains("FooMedia", $config["contexts"]["context_foo"]["managed"]);
        $this->assertContains("BarMedia", $config["contexts"]["context_foo"]["managed"]);
        
        $this->assertArrayHasKey("provider_foo", $config["contexts"]["context_foo"]["providers"]);
        $this->assertArrayHasKey("formats", $config["contexts"]["context_foo"]["providers"]["provider_foo"]);
        $this->assertArrayHasKey("format_foo", $config["contexts"]["context_foo"]["providers"]["provider_foo"]["formats"]);
        $this->assertArrayHasKey("transformer", $config["contexts"]["context_foo"]["providers"]["provider_foo"]["formats"]["format_foo"]);
        $this->assertArrayHasKey("options", $config["contexts"]["context_foo"]["providers"]["provider_foo"]["formats"]["format_foo"]);
        $this->assertArrayHasKey("height", $config["contexts"]["context_foo"]["providers"]["provider_foo"]["formats"]["format_foo"]["options"]);
        $this->assertArrayHasKey("width", $config["contexts"]["context_foo"]["providers"]["provider_foo"]["formats"]["format_foo"]["options"]);
        
        $this->assertArrayHasKey("provider_bar", $config["contexts"]["context_foo"]["providers"]);
        $this->assertArrayHasKey("format_foo", $config["contexts"]["context_foo"]["providers"]["provider_bar"]["formats"]);
        $this->assertArrayHasKey("transformer", $config["contexts"]["context_foo"]["providers"]["provider_bar"]["formats"]["format_foo"]);
        $this->assertArrayHasKey("options", $config["contexts"]["context_foo"]["providers"]["provider_bar"]["formats"]["format_foo"]);
        $this->assertArrayHasKey("height", $config["contexts"]["context_foo"]["providers"]["provider_bar"]["formats"]["format_foo"]["options"]);
        $this->assertArrayHasKey("width", $config["contexts"]["context_foo"]["providers"]["provider_bar"]["formats"]["format_foo"]["options"]);
    }
}
