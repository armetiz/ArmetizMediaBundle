<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class NotFoundException extends Exception {

    public function __construct() {
        parent::__construct("Media not found");
    }

}