<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;

class NoTemplateException extends MediaException {

    public function __construct(MediaInterface $media, $formatName) {
        parent::__construct(sprintf("No template defined for Media '%s' & format '%s'", get_class($media), $formatName));
    }

}