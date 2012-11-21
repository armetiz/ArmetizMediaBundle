<?php

namespace Armetiz\MediaBundle\Exceptions;

class UnknowMimeTypeException extends MediaException {

    public function __construct() {
        parent::__construct("Unknown mime type.");
    }

}