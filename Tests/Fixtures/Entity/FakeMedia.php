<?php

namespace Armetiz\MediaBundle\Tests\Fixtures\Entity;

use Armetiz\MediaBundle\Entity\MediaInterface;

class FakeMedia implements MediaInterface {
    /**
     * @var string
     */
    protected $mimeType = null;

    /**
     * @var binary
     */
    protected $media;

    /**
     * @var string
     */
    protected $mediaIdentifier = null;

    /**
     * @var array
     */
    protected $meta = null;

    /**
     * @var array
     */
    protected $dateCreation = null;

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

    public function getMimeType() {
        return $this->mimeType;
    }

    public function setMimeType($value) {
        $this->mimeType = $value;
    }
    
    public function getMeta() {
        return $this->meta;
    }

    public function setMeta(array $value) {
        $this->meta = $value;
    }
}