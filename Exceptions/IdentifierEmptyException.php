<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class IdentifierEmptyException extends Exception {

    public function __construct() {
        parent::__construct("Media identifier is empty.");
    }

}