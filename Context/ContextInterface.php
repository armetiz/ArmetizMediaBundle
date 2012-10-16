<?php

namespace Armetiz\MediaBundle\Context;

interface ContextInterface
{
    public function getName();
    
    public function getProvider();
    
    public function getFormats();
    
    public function hasManagedClass ($value);
    public function isManaged ($value);
}