<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class UnknowMimeTypeException extends Exception {

    public function __construct() {
        parent::__construct("Unknown mime type.");
    }

}