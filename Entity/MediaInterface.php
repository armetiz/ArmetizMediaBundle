<?php

namespace Armetiz\MediaBundle\Entity;

interface MediaInterface
{
    /**
     * Use to store the File or the path to temporary media.
     */
    public function getMedia();
    public function setMedia($value);

    /**
     * Use to store the name of the media.
     */
    public function getMediaIdentifier();
    public function setMediaIdentifier($value);
    
    /**
     * Use to store the type/mime of the media
     */
    public function getMimeType();
    public function setMimeType($value);
    
    /**
     * Use to store any time of scalar meta
     */
    public function getMeta();
    public function setMeta(array $value);
    
    public function setDateCreation(\DateTime $value);
    public function getDateCreation();
}