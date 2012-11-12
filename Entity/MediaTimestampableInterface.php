<?php

namespace Armetiz\MediaBundle\Entity;

interface MediaTimestampableInterface extends MediaInterface
{   
    public function getDateCreation();
}