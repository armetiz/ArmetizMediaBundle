<?php

namespace Armetiz\MediaBundle\Entity;

use Armetiz\MediaBundle\Entity\MediaInterface;

class Media implements MediaInterface {
    /**
     * @var string
     */
    protected $contentType = null;

    /**
     * @var binary
     */
    protected $media;

    /**
     * @var string
     */
    protected $mediaIdentifier = null;
    
    /**
     * @var string
     */
    protected $meta;

    /**
     * @var Date
     */
    protected $dateCreation;
    
    protected $annexe;
    
    public function __construct() {
        $this->dateCreation = new \DateTime ();
    }
    
    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $value) {
        $this->dateCreation = $value;
    }

    public function getMedia() {
        return $this->media;
    }

    public function setMedia($value) {
        $this->media = $value;
    }

    public function getMediaIdentifier() {
        return $this->mediaIdentifier;
    }

    public function setMediaIdentifier($value) {
        $this->mediaIdentifier = $value;
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function setContentType($value) {
        $this->contentType = $value;
    }
    
    public function getMeta() {
        return $this->meta;
    }
    
    public function setMeta(array $value) {
        $this->meta = $value;
    }
}