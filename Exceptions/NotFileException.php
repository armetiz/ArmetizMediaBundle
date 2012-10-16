<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class NotFileException extends Exception {

    public function __construct() {
        parent::__construct("A file is excepted. 'File' or a real path.");
    }

}