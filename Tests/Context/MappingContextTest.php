<?php

namespace Armetiz\MediaBundle\Tests\Context;

use Armetiz\MediaBundle\Context\Context;
use Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia;
use Armetiz\MediaBundle\Format;

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
            "provider_foo" => 
                array (
                    $this->getMockBuilder("Armetiz\MediaBundle\Format")->disableOriginalConstructor()->getMock(),
                ),
            );
        
        $context = new Context();
        $context->setProviders($providers, $formats);
        
        $providerFormats = $context->getFormats($provider);
        
        $this->assertNotNull($providerFormats);
    }
}