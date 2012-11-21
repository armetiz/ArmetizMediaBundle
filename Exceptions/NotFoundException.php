<?php

namespace Armetiz\MediaBundle\Exceptions;

class NotFoundException extends MediaException {

    public function __construct() {
        parent::__construct("Media not found");
    }

}