<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;

class NotSupportedFormatException extends MediaException {

    public function __construct(MediaInterface $media, $format) {
        parent::__construct(sprintf("Format is not supported '%s' for media '%s'", $format, get_class($media)));
    }

}