<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class EmptyException extends Exception {

    public function __construct() {
        parent::__construct("Media is empty.");
    }

}