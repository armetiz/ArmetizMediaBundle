<?php

namespace Armetiz\MediaBundle\Exceptions;

class EmptyException extends MediaException {

    public function __construct() {
        parent::__construct("Media is empty.");
    }

}