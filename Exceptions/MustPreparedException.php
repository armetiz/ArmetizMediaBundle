<?php

namespace Armetiz\MediaBundle\Exceptions;

use Exception;

class MustPreparedException extends Exception {

    public function __construct() {
        parent::__construct("Use AbstractProvider::prepareMedia before save.");
    }

}