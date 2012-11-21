<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;

class NoTemplateException extends MediaException {

    public function __construct(MediaInterface $media) {
        parent::__construct(sprintf("No template defined for Media '%s'", get_class($media)));
    }

}