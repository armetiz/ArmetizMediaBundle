<?php

namespace Armetiz\MediaBundle\Exceptions;

class MustPreparedException extends MediaException {

    public function __construct() {
        parent::__construct("Use AbstractProvider::prepareMedia before save.");
    }

}