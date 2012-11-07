<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class NotSupportedMediaException extends Exception {

    public function __construct() {
        parent::__construct("Media is not supported");
    }

}