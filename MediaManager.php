<?php

namespace Leezy\MediaBundle;

use Leezy\MediaBundle\Models\MediaInterface;
use Leezy\MediaBundle\Models\MediaAdvancedInterface;
use Leezy\MediaBundle\Context\ContextInterface;
use Leezy\MediaBundle\Event\MediaEvent;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MediaManager 
{
    protected $contexts;
    protected $dispatcher;
    
    public function __construct (EventDispatcherInterface $dispatcher = null)
    {
        $this->contexts = array();
        $this->dispatcher = $dispatcher;
    }
    
    public function addContext (ContextInterface $context)
    {
        $this->contexts[$context->getName()] = $context;
    }
        
    protected function getContext ($value) {
        $name = "";
        
        if ($value instanceof MediaInterface)
        {
            foreach ($this->contexts as $context) {
                if ($context->isManaged ($value))
                    return $context;
            }
        }
        elseif (is_string($value)) {
            $name = $value;
        }
        
        if (!$this->hasContext($name)) {
            if ($name)
                throw new \InvalidArgumentException(sprintf("Context '%s' doesn't exist", $name));
            else
                throw new \InvalidArgumentException(sprintf("Context for class '%s' doesn't exist", get_class($value)));
                
        }
        
        return $this->contexts[$name];
    }
    
    protected function hasContext($name)
    {
        return array_key_exists($name, $this->contexts);
    }
    
    protected function getProvider (MediaInterface $media)
    {       
        return $this->getContext($media)->getProvider();
    }
    
    public function saveMedia (MediaInterface $media)
    {
        $this->getProvider ($media)->saveMedia ($media);
               
        if ( $this->dispatcher )
            $this->dispatcher->dispatch (MediaEvent::UPDATE, new MediaEvent(($media)));
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        $provider = $this->getProvider ($media);
        $provider->prepareMedia ($media);
        $provider->deleteMedia ($media);
        
        if ( $this->dispatcher )
            $this->dispatcher->dispatch (MediaEvent::DELETE, new MediaEvent(($media)));
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        return $this->getProvider ($media)->prepareMedia ($media);
    }
    
    public function getUri (MediaInterface $media)
    {
        return $this->getProvider ($media)->getUri ($media);
    }
    
    public function getPath (MediaInterface $media)
    {
        return $this->getProvider ($media)->getPath ($media);
    }
    
    public function getTemplate (MediaInterface $media)
    {
        return $this->getProvider ($media)->getTemplate ();
    }
    
    public function getRaw (MediaInterface $media)
    {
        return $this->getProvider ($media)->getRaw ($media);
    }
}