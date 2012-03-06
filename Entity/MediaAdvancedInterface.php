<?php

namespace Leezy\MediaBundle\Entity;

interface MediaAdvancedInterface extends MediaInterface
{   
    public function getContentType();
    public function setContentType($value);
    
    public function getMediaIdentifierBase();
    public function setMediaIdentifierBase($value);
}