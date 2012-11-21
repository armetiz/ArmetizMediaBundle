<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;

class NoContextException extends MediaException {

    public function __construct(MediaInterface $media) {
        parent::__construct(sprintf("Context for class '%s' doesn't exist", get_class($media)));
    }

}