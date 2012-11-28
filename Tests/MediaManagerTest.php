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
}