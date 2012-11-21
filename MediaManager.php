<?php

namespace Armetiz\MediaBundle;

use Armetiz\MediaBundle\Event\MediaEvent;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Context\ContextInterface;
use Armetiz\MediaBundle\Exceptions\NoContextException;
use Armetiz\MediaBundle\Exceptions\NoProviderException;
use Armetiz\MediaBundle\Exceptions\NotSupportedFormatException;

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
    
    final protected function getFormats (MediaInterface $media)
    {
        return $this->getContext($media)->getProviderFormats($this->getProvider($media));
    }
    
    final protected function checkProvider(MediaInterface $media)
    {
        if(!$this->getProvider($media)) {
            throw new NoProviderException($media, $this->getContext($media));
        }
    }
    
    final protected function checkFormat(MediaInterface $media, $format)
    {
        $formats = $this->getContext($media)->getFormats($media);
        
        if (null === $formats) {
            throw new NotSupportedFormatException($media, $format);
        }
        
        if(!array_key_exists($format, $formats)) {
            throw new NotSupportedFormatException($media, $format);
        }
    }
    
    public function saveMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->saveMedia ($media);
               
        if ( $this->dispatcher )
            $this->dispatcher->dispatch (MediaEvent::UPDATE, new MediaEvent(($media)));
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        
        $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->prepareMedia ($media)
            ->deleteMedia ($media);
        
        if ( $this->dispatcher )
            $this->dispatcher->dispatch (MediaEvent::DELETE, new MediaEvent(($media)));
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        $this->checkProvider($media);
        
        return $this->getProvider ($media)->prepareMedia ($media);
    }
    
    public function getUri (MediaInterface $media, $format)
    {
        $this->checkProvider($media);
        $this->checkFormat($media, $format);
        
        return $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->getUri ($media, $format);
    }
    
    public function getRenderOptions (MediaInterface $media, $format)
    {
        $this->checkProvider($media);
        $this->checkFormat($media, $format);
        
        return $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->getRenderOptions ($media, $format);
    }
    
    public function getTemplate (MediaInterface $media, $name = "default")
    {
        $this->checkProvider($media);
        return $this->getProvider ($media)
            ->setFormats($this->getFormats($media))
            ->getTemplate ($name);
    }
}