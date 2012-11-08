<?php

namespace Armetiz\MediaBundle;

use Doctrine\Common\Collections\Collection;

use Armetiz\MediaBundle\Event\MediaEvent;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Entity\FormatInterface;
use Armetiz\MediaBundle\Context\ContextInterface;
use Armetiz\MediaBundle\Exceptions\NoContextException;
use Armetiz\MediaBundle\Exceptions\NoProviderException;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MediaManager 
{
    private $contexts;
    private $dispatcher;
    
    public function __construct (EventDispatcherInterface $dispatcher = null)
    {
        $this->contexts = array();
        $this->dispatcher = $dispatcher;
    }
    
    public function addContext (ContextInterface $context)
    {
        $this->contexts[$context->getName()] = $context;
    }
        
    final protected function getContext ($value) {
        if ($value instanceof MediaInterface)
        {
            foreach ($this->contexts as $context) {
                if ($context->isManaged ($value))
                    return $context;
            }
            
            throw new NoContextException($value);
        }
        elseif (is_string($value) && $this->hasContext($value)) {
            return $this->contexts[$value];
        }
        
        return null;
    }
    
    final protected function hasContext($name)
    {
        return array_key_exists($name, $this->contexts);
    }
    
    final protected function getProvider (MediaInterface $media)
    {       
        return $this->getContext($media)->getProvider($media);
    }
    
    final protected function checkProvider(MediaInterface $media)
    {
        if(!$this->getProvider($media)) {
            throw new NoProviderException($media, $this->getContext($media));
        }
    }
    
    public function saveMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        $this->getProvider ($media)->saveMedia ($media);
               
        if ( $this->dispatcher )
            $this->dispatcher->dispatch (MediaEvent::UPDATE, new MediaEvent(($media)));
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        $provider = $this->getProvider ($media);
        $provider->prepareMedia ($media);
        $provider->deleteMedia ($media);
        
        if ( $this->dispatcher )
            $this->dispatcher->dispatch (MediaEvent::DELETE, new MediaEvent(($media)));
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        return $this->getProvider ($media)->prepareMedia ($media);
    }
    
    public function getUri (MediaInterface $media)
    {
        $this->checkProvider($media);
        return $this->getProvider ($media)->getUri ($media);
    }
    
    public function getPath (MediaInterface $media)
    {
        $this->checkProvider($media);
        return $this->getProvider ($media)->getPath ($media);
    }
    
    public function getRaw (MediaInterface $media)
    {
        $this->checkProvider($media);
        return $this->getProvider ($media)->getRaw ($media);
    }
    
    public function getTemplate (MediaInterface $media, $name = "default")
    {
        $this->checkProvider($media);
        return $this->getProvider ($media)->getTemplate ($name);
    }
    
    public function findMedia ($value, $format = null) {
        $media = null;
        
        if ($value instanceof Collection) {
            foreach ($value as $mediaBlack) {
                if ($mediaBlack instanceof FormatInterface && $format == $mediaBlack->getFormat()) {
                    $media = $mediaBlack;
                    break;
                }
            }
        }
        else {
            $media = $value;
        }
        
        if ( !($media instanceof MediaInterface)) {
            return null;
        }
        
        return $media;
    }
}