<?php

namespace Armetiz\MediaBundle\Exceptions;

class NotFileException extends MediaException {

    public function __construct() {
        parent::__construct("A file is excepted. 'File' or a real path.");
    }

}