<?php

namespace Armetiz\MediaBundle\Exceptions;

class NotValidException extends MediaException {

    public function __construct($property, $attempt, $get) {
        parent::__construct("Media is not valid : property " . $property . " have to be " . $attempt . ", get : " . $get);
    }

}