<?php

namespace Leezy\MediaBundle\Models;

interface MediaInterface
{
    public function getMedia();
    public function setMedia($value);
    
    public function getMediaIdentifier();
    public function setMediaIdentifier($value);
}