<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\SystemBundle\Exceptions\ArgumentException;
use Armetiz\MediaBundle\Entity\MediaInterface;

class Context implements ContextInterface
{
    private $name;
    private $providers;
    private $managedClasses;
    private $formats;
    
    public function __construct()
    {
        $this->name = uniqid("media_context_");
        $this->formats = array("origin" => null);
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
    
    
    public function getFormats(MediaInterface $media)
    {
        $provider = $this->getProvider($media);
        
        return $this->getProviderFormats($provider);
    }
    
    public function getProviderFormats($provider)
    {
        if (!in_array($provider, $this->providers)) {
            return null;
        }
        
        $providerName = array_search($provider, $this->providers, true);

        if(array_key_exists($providerName, $this->formats)) {
            if ($provider->getDefaultFormats()) {
                return array_merge($this->formats[$providerName], $provider->getDefaultFormats());
            }
            else {
                return $this->formats[$providerName];
            }
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