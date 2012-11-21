<?php

namespace Armetiz\MediaBundle\Exceptions;

class IdentifierEmptyException extends MediaException {

    public function __construct() {
        parent::__construct("Media identifier is empty.");
    }

}