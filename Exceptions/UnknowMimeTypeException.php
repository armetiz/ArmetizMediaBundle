<?php

namespace Armetiz\MediaBundle\Exceptions;

class UnknowMimeTypeException extends MediaException {

    public function __construct($mimeType) {
        parent::__construct(sprintf("Unknown mime type: '%s'", $mimeType));
    }

}