<?php

namespace Armetiz\MediaBundle\Exceptions;

class NotSupportedMediaException extends MediaException {

    public function __construct() {
        parent::__construct("Media is not supported");
    }

}