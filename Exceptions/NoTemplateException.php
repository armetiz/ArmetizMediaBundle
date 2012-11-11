<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Provider\ProviderInterface;

use Exception;

class NoTemplateException extends Exception {

    public function __construct(MediaInterface $media, ProviderInterface $provider) {
        parent::__construct(sprintf("No template defined for Provider '%s' and Media '%s'", get_class($provider), get_class($media)));
    }

}