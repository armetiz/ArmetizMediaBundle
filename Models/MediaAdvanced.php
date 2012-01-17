<?php

namespace Leezy\MediaBundle\Models;

class MediaAdvanced extends Media implements MediaAdvancedInterface
{   
    private $contentType;
    private $mediaIdentifierBase;
    private $format;
    
    public function getContentType() {
        return $this->contentType;
    }
    
    public function setContentType($value) {
        $this->contentType = $value;
    }
    
    public function getMediaIdentifierBase() {
        return $this->mediaIdentifierBase;
    }
    
    public function setMediaIdentifierBase($value) {
        $this->mediaIdentifierBase = $value;
    }
    
    public function getFormat() {
        return $this->format;
    }
    
    public function setFormat($value) {
        $this->format = $value;
    }
}