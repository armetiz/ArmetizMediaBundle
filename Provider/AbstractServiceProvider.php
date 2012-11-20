<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

use Armetiz\MediaBundle\Exceptions\NotSupportedMediaException;

abstract class AbstractServiceProvider extends AbstractProvider
{
    abstract public function getMimeType();
    
    abstract public function getMediaPattern();
    
    public function validate (MediaInterface $media) {}
    
    public function canHandleMedia (MediaInterface $media)
    {
        return (($this->getMimeType() === $media->getContentType()) || $media->getMedia() && (preg_match($this->getMediaPattern(), $media->getMedia()) > 0));
    }
    
    public function saveMedia (MediaInterface $media)
    {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
        
        $this->validate($media);
        
        return $this;
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
        
        return $this;
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        parent::prepareMedia($media);
        
        $media->setMediaIdentifier($media->getMedia());
        $media->setContentType($this->getMimeType());
        
        return $this;
    }
    
    public function getRaw (MediaInterface $media)
    {
        preg_match($this->getMediaPattern(), $media->getMediaIdentifier(), $matches);
        return $matches[1];
    }
    
    public function getUri (MediaInterface $media)
    {
        return $media->getMediaIdentifier();
    }
    
    public function getPath (MediaInterface $media)
    {
        return $media->getMediaIdentifier();
    }
}