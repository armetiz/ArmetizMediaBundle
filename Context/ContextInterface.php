<?php

namespace Armetiz\MediaBundle\Context;

use Armetiz\MediaBundle\Entity\MediaInterface;

interface ContextInterface
{
    public function setName($value);
    public function getName();
    
    public function getProvider(MediaInterface $media);
    public function getProviders();
    public function setProviders(array $value);
    
    public function getFormats();
    public function setFormats(array $value);
    
    public function setManagedClasses(array $value);
    public function hasManagedClass ($value);
    public function isManaged ($value);
}