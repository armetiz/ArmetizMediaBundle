<?php

namespace Leezy\MediaBundle\Context;

use Leezy\MediaBundle\Provider\ProviderInterface;

interface ContextInterface
{
    public function getName();
    
    public function getProvider();
    
    public function getFormats();
    
    public function hasManagedClass ($value);
    public function isManaged ($value);
}