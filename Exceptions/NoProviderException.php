<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Context\ContextInterface;

use Exception;

class NoProviderException extends Exception {

    public function __construct(MediaInterface $media, ContextInterface $context = null) {
        if ($media->getMedia()) {
            if (!is_string($media->getMedia())) {
                parent::__construct(sprintf("Providers doesn't handle the media '%s' inside context '%s'.", get_class($media), $context->getName()));
            }
            else {
                parent::__construct(sprintf("Providers doesn't handle the media '%s' inside context '%s'. content '%s'", get_class($media), $context->getName(), $media->getMedia()));
            }
        }
        else {
            parent::__construct(sprintf("Providers doesn't handle the media '%s' inside context '%s'.", get_class($media), $context->getName()));
        }
        
    }

}