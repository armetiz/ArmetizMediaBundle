<?php

namespace Leezy\MediaBundle\Models;

use Leezy\MediaBundle\Models\MediaInterface;

interface MediaAdvancedInterface extends MediaInterface
{   
    public function getContentType();
    public function setContentType($value);
    
    public function getMediaIdentifierBase();
    public function setMediaIdentifierBase($value);
}