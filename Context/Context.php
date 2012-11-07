<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\SystemBundle\Exceptions\ArgumentException;
use Armetiz\MediaBundle\Entity\MediaInterface;

class Context implements ContextInterface
{
    private $name;
    private $providers;
    private $formats;
    private $managedClasses;
    
    public function __construct()
    {
        $this->name = uniqid("media_context_");
        
        $this->providers = array();
        $this->managedClasses = array();
        $this->formats = array();
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getProvider(MediaInterface $media)
    {
        foreach($this->providers as $provider)
        {
            if ($provider->canHandleMedia($media))
            {
                return $provider;
            }
        }
        
        return null;
    }
    
    public function getProviders()
    {
        return $this->providers;
    }
    
    public function setProviders(array $value)
    {
        $formats = $this->formats;
        array_walk($value, function(&$provider) use ($formats) { 
            $provider->setFormats($formats);
        });
        
        $this->providers = $value;
    }
    
    public function setFormats(array $value)
    {
        $this->formats = $value;
        
        array_walk($value, function(&$provider) { 
            $provider->setFormats($this->formats);
        });
    }
    
    public function getFormats()
    {
        return $this->formats;
    }
    
    public function setManagedClasses(array $value) 
    {
        $this->managedClasses = $value;
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