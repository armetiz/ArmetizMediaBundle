<?php

namespace Leezy\MediaBundle\Context;

use Leezy\MediaBundle\Provider\ProviderInterface;
use Leezy\SystemBundle\Exceptions\ArgumentException;

class Context implements ContextInterface
{
    protected $name;
    protected $provider;
    protected $formats;
    protected $managedClasses;
    
    public function __construct($name, ProviderInterface $provider)
    {
        $this->name = $name;
        $this->provider = $provider;
        $this->managedClasses = array ();
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getProvider()
    {
        return $this->provider;
    }
    
    public function setProvider(ProviderInterface $value)
    {
        $this->provider = $value;
    }
    
    public function getFormats()
    {
        return $this->formats;
    }
    
    public function setFormats(array $value)
    {
        $this->formats = $value;
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
    
    public function addManagedClass ($value)
    {
        $this->managedClasses[] = $value;
    }
    
    public function hasManagedClass ($value)
    {
        return (false !== array_search($value, $this->managedClasses));
    }
}