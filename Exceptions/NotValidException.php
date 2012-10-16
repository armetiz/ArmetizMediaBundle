<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class NotValidException extends Exception {

    public function __construct($property, $attempt, $get) {
        parent::__construct("Media is not valid : property " . $property . " have to be " . $attempt . ", get : " . $get);
    }

}