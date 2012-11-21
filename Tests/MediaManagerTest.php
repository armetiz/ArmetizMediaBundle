<?php

namespace Armetiz\MediaBundle\Tests\Context;

use Armetiz\MediaBundle\Context\Context;
use Armetiz\MediaBundle\MediaManager;
use Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia;

use ReflectionClass;

class MediaManagerTest  extends \PHPUnit_Framework_TestCase {
    public function testGetContextForMappedClass() {
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia"
        );
        
        $context = new Context();
        $context->setManagedClasses($managedClasses);
        
        $mediaManager = new MediaManager();
        $mediaManager->addContext($context);
        
        $media = new FakeMedia();
        
        $mediaManagerClass = new ReflectionClass($mediaManager);
        
        $methodGetContext = $mediaManagerClass->getMethod("getContext");
        $methodGetContext->setAccessible(true);
        
        $this->assertTrue($context === $methodGetContext->invokeArgs($mediaManager, array($media)));
    }
    
    /**
     * @expectedException Armetiz\MediaBundle\Exceptions\NoContextException
     */
    public function testGetContextForNotMappedClass() {
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\Fake"
        );
        
        $context = new Context();
        $context->setManagedClasses($managedClasses);
        
        $mediaManager = new MediaManager();
        $mediaManager->addContext($context);
        
        $media = new FakeMedia();
        
        $mediaManagerClass = new ReflectionClass($mediaManager);
        
        $methodGetContext = $mediaManagerClass->getMethod("getContext");
        $methodGetContext->setAccessible(true);
        $methodGetContext->invokeArgs($mediaManager, array($media));
    }
    
    /**
     * @expectedException Armetiz\MediaBundle\Exceptions\NotSupportedFormatException
     */
    public function testHasFormatWhenNotSupported() {
        $provider = $this->getMockBuilder("Armetiz\MediaBundle\Provider\FileProvider")->disableOriginalConstructor()->getMock();
        $provider->expects($this->once())
             ->method('canHandleMedia')
             ->will($this->returnValue(true));
        
        $providers = array (
            "provider_foo" => $provider
        );
        
        $formats = array(
            "provider_foo" => array(
                "foo" => array(),
                "bar" => array(),
            )
        );
        
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia"
        );
        
        $context = new Context();
        $context->setProviders($providers, $formats);
        $context->setManagedClasses($managedClasses);
        
        $mediaManager = new MediaManager();
        $mediaManager->addContext($context);
        
        $media = new FakeMedia();
        
        $mediaManagerClass = new ReflectionClass($mediaManager);
        
        $methodGetContext = $mediaManagerClass->getMethod("checkFormat");
        $methodGetContext->setAccessible(true);
        $methodGetContext->invokeArgs($mediaManager, array($media, "not_supported_format"));
    }
    
    public function testHasFormatWhenSupported() {
        $provider = $this->getMockBuilder("Armetiz\MediaBundle\Provider\FileProvider")->disableOriginalConstructor()->getMock();
        $provider->expects($this->once())
             ->method('canHandleMedia')
             ->will($this->returnValue(true));
        
        $providers = array (
            "provider_foo" => $provider
        );
        
        $formats = array(
            "provider_foo" => array(
                "foo" => array(),
                "bar" => array(),
            )
        );
        
        $managedClasses = array(
            "Armetiz\MediaBundle\Tests\Fixtures\Entity\FakeMedia"
        );
        
        $context = new Context();
        $context->setProviders($providers, $formats);
        $context->setManagedClasses($managedClasses);
        
        $mediaManager = new MediaManager();
        $mediaManager->addContext($context);
        
        $media = new FakeMedia();
        
        $mediaManagerClass = new ReflectionClass($mediaManager);
        
        $methodGetContext = $mediaManagerClass->getMethod("checkFormat");
        $methodGetContext->setAccessible(true);
        $methodGetContext->invokeArgs($mediaManager, array($media, "foo"));
    }
}