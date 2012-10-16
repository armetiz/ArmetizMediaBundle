<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\MediaBundle\Provider\ProviderInterface;
use Armetiz\SystemBundle\Exceptions\ArgumentException;

class Context implements ContextInterface
{
    private $name;
    private $provider;
    private $formats;
    private $managedClasses;
    
    public function __construct($name, ProviderInterface $provider, array $managedClasses = array(), array $formats = array())
    {
        $this->name = $name;
        $this->managedClasses = $managedClasses;
        $this->formats = $formats;
        
        $this->provider = $provider;
        $this->provider->setFormats ($this->getFormats());
    }
    
    public function getName()
    {
        return $this->name;
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