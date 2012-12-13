<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;

class NoTemplateException extends MediaException {

    public function __construct(MediaInterface $media, $formatName) {
        parent::__construct(sprintf("No template defined for Media mime-type '%s' & format '%s'", $media->getMimeType(), $formatName));
    }

}