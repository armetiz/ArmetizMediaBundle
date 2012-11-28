<?php

namespace Armetiz\MediaBundle;

use Armetiz\MediaBundle\Event\MediaEvent;
use Armetiz\MediaBundle\Entity\MediaInterface;
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
        
    public function saveMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        
        $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->saveMedia ($media);
               
        if ( $this->dispatcher ) {
            $this->dispatcher->dispatch (MediaEvent::UPDATE, new MediaEvent(($media)));
        }
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        
        $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->prepareMedia ($media)
            ->deleteMedia ($media);
        
        if ( $this->dispatcher ) {
            $this->dispatcher->dispatch (MediaEvent::DELETE, new MediaEvent(($media)));
        }
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        
        return $this->getProvider($media)->prepareMedia($media);
    }
    
    public function getUri (MediaInterface $media, $formatName)
    {
        $this->checkProvider($media);
        
        return $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->getUri($media, $this->getFormat($media, $formatName));
    }
    
    public function getRenderOptions (MediaInterface $media, $formatName)
    {
        $this->checkProvider($media);
        
        return $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->getRenderOptions($media, $this->getFormat($media, $formatName));
    }
    
    public function getTemplate (MediaInterface $media, $formatName)
    {
        $this->checkProvider($media);
        
        return $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->getTemplate($this->getFormat($media, $formatName));
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
    
    final protected function getFormats (MediaInterface $media)
    {
        return $this->getContext($media)->getFormats($this->getProvider($media));
    }
    
    final protected function getFormat (MediaInterface $media, $formatName)
    {
        return $this->getContext($media)->getFormat($media, $formatName);
    }
    
    final protected function checkProvider(MediaInterface $media)
    {
        if(!$this->getProvider($media)) {
            throw new NoProviderException($media, $this->getContext($media));
        }
    }
}