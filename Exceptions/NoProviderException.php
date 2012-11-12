<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Context\ContextInterface;

use Symfony\Component\HttpFoundation\File\File;

use Exception;

class NoProviderException extends Exception {

    public function __construct(MediaInterface $media, ContextInterface $context = null) {
        $file = null;
        $mimeType = null;
        
        if ($media->getMedia()) {
            if ($media->getMedia() instanceof File) {
                $file = $media->getMedia();
            }
            elseif (is_file($media->getMedia())) {
                $file = new File($media->getMedia());
            }
        }
        
        if ($media->getContentType()) {
            $mimeType = $media->getContentType();
        }
        elseif ($file) {
            $mimeType = $file->getMimeType();
        }
        
        parent::__construct(sprintf("Providers doesn't handle a media, class: '%s'.\nContext: '%s'.\nMimeType: '%s'", get_class($media), $context->getName(), $mimeType));
    }

}