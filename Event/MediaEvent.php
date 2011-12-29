<?php

namespace Leezy\MediaBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Leezy\MediaBundle\Models\MediaInterface;

class MediaEvent extends Event
{
    const UPDATE = "mediaUpdateEvent";
    const DELETE = "mediaDeleteEvent";
    
    protected $media;
    
    public function __construct (MediaInterface $media)
    {
        $this->media = $media;
    }
    
    public function getMedia ()
    {
        return $this->media;
    }
}