<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Context\ContextInterface;

use Exception;

class NoProviderException extends Exception {

    public function __construct(MediaInterface $media, ContextInterface $context = null) {
        parent::__construct(sprintf("Providers doesn't handle the media '%s' inside context '%s'", get_class($media), $context->getName()));
    }

}