<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\SystemBundle\Exceptions\ArgumentException;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Provider\ProviderInterface;
use Armetiz\MediaBundle\Format;

class Context implements ContextInterface
{
    private $name;
    private $providers;
    private $managedClasses;
    private $formats;
    
    public function __construct()
    {
        $this->name = uniqid("media_context_");
        $this->formats = array();
        $this->providers = array();
        $this->managedClasses = array();
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($value)
    {
        $this->name = $value;
        
        return $this;
    }
    
    public function getFormat(MediaInterface $media, $formatName)
    {
        $provider = $this->getProvider($media);
        
        $formats = $this->getFormats($provider);
        
        foreach($formats as $format) {
            if ($format->getName() === $formatName) {
                return $format;
            }
        }
        
        return null;
    }
    
    public function getFormats(ProviderInterface $provider)
    {
        if (!in_array($provider, $this->providers)) {
            return null;
        }
        
        $providerName = array_search($provider, $this->providers, true);

        if(array_key_exists($providerName, $this->formats)) {
            return $this->formats[$providerName];
        }
        else {
            return null;
        }
    }
    
    public function getProvider(MediaInterface $media)
    {
        foreach($this->providers as $provider) {
            if ($provider->canHandleMedia($media)) {
                return $provider;
            }
        }
        
        return null;
    }
    
    public function getProviders()
    {
        return $this->providers;
    }
    
    public function setProviders(array $providers, array $formats = array())
    {
        //TODO: Do something more simple!!
        
        foreach($providers as $provider) {
            if (!($provider instanceof ProviderInterface)) {
                throw new \InvalidArgumentException("Formats have to be a collection of Armetiz\MediaBundle\Provider\ProviderInterface");
            }
        }
        
        foreach($formats as $formatsByProvider) {
            if (!is_array($formatsByProvider)) {
                throw new \InvalidArgumentException("Formats by provider have to be an array");
            }
            
            foreach($formatsByProvider as $format) {
                if (!($format instanceof Format)) {
                    throw new \InvalidArgumentException("Formats by provider have to be a collection of Armetiz\MediaBundle\Format");
                }
            }
        }
        
        $this->providers = $providers;
        $this->formats = $formats;
        
        return $this;
    }
    
    public function setManagedClasses(array $value) 
    {
        $this->managedClasses = $value;
        
        return $this;
    }
    
    public function isManaged ($value) {
        if (!$this->managedClasses) {
            return false;
        }
        
        if (is_string($value)) {
            return $this->hasManagedClass ($value);
        }
        
        if (!is_object($value)) {
            throw new ArgumentException("isManaged attempt a string or an object. " . gettype($value) . " given.");
        }
        
        foreach ($this->managedClasses as $managedClass) {
            if ($value instanceof $managedClass) {
                return true;
            }
        }
        
        return false;
    }
    
    public function hasManagedClass ($value)
    {
        return (false !== array_search($value, $this->managedClasses));
    }
}