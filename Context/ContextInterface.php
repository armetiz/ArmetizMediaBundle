<?php

namespace Leezy\MediaBundle\Context;

use Leezy\MediaBundle\Provider\ProviderInterface;

interface ContextInterface
{
    public function getName();
    public function setName($value);
    
    public function getProvider();
    public function setProvider(ProviderInterface $value);
    
    public function getFormats();
    
    public function hasManagedClass ($value);
    public function addManagedClass ($value);
    public function isManaged ($value);
}