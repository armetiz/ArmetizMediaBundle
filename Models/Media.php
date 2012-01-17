<?php

namespace Leezy\MediaBundle\Models;

class Media implements MediaInterface
{   
    private $media;
    private $mediaIdentifier;
    
    /**
     * Use to store the File or the path to temporary file.
     */
    public function getMedia() {
        return $this->media;
    }
    
    public function setMedia($value) {
        $this->media = $value;
    }

    /**
     * Use to store the name of the file.
     */
    public function getMediaIdentifier() {
        return $this->mediaIdentifier;
    }
    
    public function setMediaIdentifier($value) {
        $this->mediaIdentifier = $value;
    }
}