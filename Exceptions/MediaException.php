<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class MediaException extends Exception {

    public function __construct($message = "Media exception.") {
        parent::__construct($message);
    }

}