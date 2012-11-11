<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

use Armetiz\MediaBundle\Exceptions\NotSupportedMediaException;

class UrlProvider extends AbstractProvider
{
    const TYPE_MIME = "x-armetiz/url";
    const PATTERN = "/url\/(.*)/";
    
    public function validate (MediaInterface $media) {}
    
    public function canHandleMedia (MediaInterface $media)
    {
        return ((self::TYPE_MIME === $media->getContentType()) || $media->getMedia() && (preg_match(self::PATTERN, $media->getMedia()) > 0));
    }
    
    public function saveMedia (MediaInterface $media)
    {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
        
        $this->validate($media);
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        parent::prepareMedia($media);
        
        $media->setMediaIdentifier($media->getMedia());
        $media->setContentType(self::TYPE_MIME);
    }
    
    public function getRaw (MediaInterface $media)
    {
        preg_match(self::PATTERN, $media->getMediaIdentifier(), $matches);
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