<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Provider\FileProvider;

use Symfony\Component\HttpFoundation\File\File;

class ImageProvider extends FileProvider
{
    const TYPE_MIME_PATTERN = "/image\/(.*)/";
    
    public function canHandleMedia (MediaInterface $media)
    {
        if (preg_match(self::TYPE_MIME_PATTERN, $media->getContentType()) > 0) {
            return true;
        }
        else {
            if (is_file($media->getMedia())) {
                $file = new File($media->getMedia());
            }
            elseif ($media->getMedia() instanceof File) {
                $file = $media->getMedia();
            }
            else {
                return false;
            }
            
            return ((preg_match(self::TYPE_MIME_PATTERN, $file->getMimeType()) > 0));
        }
    }
    
    public function saveMedia (MediaInterface $media)
    {
        parent::saveMedia($media);
        
        $this->generateFormats($media);
        
        return $this;
    }
}