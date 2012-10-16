<?php

namespace Armetiz\MediaBundle\Entity;

interface MediaAdvancedInterface extends MediaInterface
{   
    public function getMediaIdentifierBase();
    public function setMediaIdentifierBase($value);
}