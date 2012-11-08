<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Context\ContextInterface;

use Exception;

class NoTemplateException extends Exception {

    public function __construct(MediaInterface $media) {
        parent::__construct(sprintf("No template defined for this Media: '%s'", get_class($media)));
    }

}