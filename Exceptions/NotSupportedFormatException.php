<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class NotSupportedFormatException extends Exception {

    public function __construct($format) {
        parent::__construct("Format is not supported : " . $format);
    }

}