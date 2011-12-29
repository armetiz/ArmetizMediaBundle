<?php

namespace Leezy\MediaBundle\Context;

use Leezy\MediaBundle\Provider\ProviderInterface;

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
    
    public function addManagedClass ($value)
    {
        $this->managedClasses[] = $value;
    }
    
    public function hasManagedClass ($value)
    {
        return (false !== array_search($value, $this->managedClasses));
    }
}