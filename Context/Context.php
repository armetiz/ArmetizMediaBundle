<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\SystemBundle\Exceptions\ArgumentException;
use Armetiz\MediaBundle\Entity\MediaInterface;

class Context implements ContextInterface
{
    private $name;
    private $providers;
    private $managedClasses;
    
    public function __construct()
    {
        $this->name = uniqid("media_context_");
        
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
        $this->providers = $value;
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