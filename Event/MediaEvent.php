<?php

namespace Armetiz\MediaBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Armetiz\MediaBundle\Entity\MediaInterface;

class MediaEvent extends Event
{
    const UPDATE = "mediaUpdateEvent";
    const DELETE = "mediaDeleteEvent";
    
    private $media;
    
    public function __construct (MediaInterface $media)
    {
        $this->media = $media;
    }
    
    public function getMedia ()
    {
        return $this->media;
    }
}