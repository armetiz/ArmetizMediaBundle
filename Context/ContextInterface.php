<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Provider\ProviderInterface;

interface ContextInterface
{
    public function setName($value);
    public function getName();
    
    public function getFormats(ProviderInterface $provider);
    
    public function getProvider(MediaInterface $media);
    public function getProviders();
    public function setProviders(array $providers, array $formats = array());
    
    public function setManagedClasses(array $value);
    public function hasManagedClass ($value);
    public function isManaged ($value);
}