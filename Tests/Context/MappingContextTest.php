<?php

namespace Armetiz\MediaBundle\Tests\Context;

use Armetiz\MediaBundle\Context\Context;
use Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia;

class MappingContextTest extends \PHPUnit_Framework_TestCase {
    public function testGetProviderExists() {
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia"
        );
        
        $provider = $this->getMockBuilder("Armetiz\MediaBundle\Provider\FileProvider")->disableOriginalConstructor()->getMock();
        $provider->expects($this->once())
             ->method('canHandleMedia')
             ->will($this->returnValue(true));
        
        $providers = array (
            $provider
        );
        
        $context = new Context();
        $context->setManagedClasses($managedClasses);
        $context->setProviders($providers);
        
        $media = new FakeMedia();
        
        $this->assertTrue($provider === $context->getProvider($media));
    }
    
    public function testGetProviderNotExists() {
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia"
        );
        
        $context = new Context();
        $context->setManagedClasses($managedClasses);
        
        $media = new FakeMedia();
        
        $this->assertNull($context->getProvider($media));
    }
    
    public function testHasFormatForProvider() {
        $provider = $this->getMockBuilder("Armetiz\MediaBundle\Provider\FileProvider")->disableOriginalConstructor()->getMock();
        $providers = array (
            "provider_foo" => $provider,
        );
        
        $formats = array (
            "provider_foo" => array(
                "foo" => array(),
                "bar" => array(),
            )
        );
        
        $context = new Context();
        $context->setProviders($providers, $formats);
        
        $providerFormats = $context->getProviderFormats($provider);
        
        $this->assertNotNull($providerFormats);
        $this->assertTrue(array_key_exists("foo", $providerFormats));
        $this->assertTrue(array_key_exists("bar", $providerFormats));
        $this->assertFalse(array_key_exists("john.doe", $providerFormats));
    }
    
    public function testHasFormatForMedia() {
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia"
        );
        
        $provider = $this->getMockBuilder("Armetiz\MediaBundle\Provider\FileProvider")->disableOriginalConstructor()->getMock();
        $provider->expects($this->once())
             ->method('canHandleMedia')
             ->will($this->returnValue(true));
        
        $providers = array (
            "provider_foo" => $provider
        );
        
        $formats = array (
            "provider_foo" => array(
                "foo" => array(),
                "bar" => array(),
            )
        );
        
        $context = new Context();
        $context->setProviders($providers, $formats);
        $context->setManagedClasses($managedClasses);
        
        $media = new FakeMedia();
        
        $mediaFormats = $context->getFormats($media);
        
        $this->assertNotNull($mediaFormats);
        $this->assertTrue(array_key_exists("foo", $mediaFormats));
        $this->assertTrue(array_key_exists("bar", $mediaFormats));
        $this->assertFalse(array_key_exists("john.doe", $mediaFormats));
    }
}