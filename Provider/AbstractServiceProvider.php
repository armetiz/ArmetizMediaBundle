<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

use Armetiz\MediaBundle\Exceptions\NotSupportedMediaException;

abstract class AbstractServiceProvider extends AbstractProvider
{
    abstract public function getMimeType();
    
    abstract public function getMediaPattern();
    
    public function canHandleMedia (MediaInterface $media)
    {
        return (($this->getMimeType() === $media->getContentType()) || $media->getMedia() && (preg_match($this->getMediaPattern(), $media->getMedia()) > 0));
    }
    
    public function saveMedia (MediaInterface $media)
    {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
        
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
    
    public function getRenderOptions (MediaInterface $media, $format, array $options = array())
    {
        preg_match($this->getMediaPattern(), $media->getMediaIdentifier(), $matches);
        
        $defaultOptions = array(
            "code" => $matches[1],
            "mime_type" => $this->getMimeType(),
        );
        
        return array_merge(parent::getRenderOptions($media, $format, $options), $defaultOptions);
    }
    
    public function getUri (MediaInterface $media, $format)
    {
        //Use some specific CDN ?
        return $media->getMediaIdentifier();
    }
    
    public function generateFormats(MediaInterface $media)
    {
        //TODO
    }
}