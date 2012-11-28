<?php

namespace Armetiz\MediaBundle\Exceptions;

use Armetiz\MediaBundle\Provider\ProviderInterface;
use Armetiz\MediaBundle\Format;

class NotSupportedFormatException extends MediaException {

    public function __construct(ProviderInterface $provider, array $formatSupported, Format $format) {
        foreach($formatSupported as $formatAvailable) {
            $formatsAvailable[] = $formatAvailable->getName();
        }
        
        parent::__construct(sprintf("Format '%s' is not supported on provider '%s'. Supported formats are '%s'", $format->getName(), get_class($provider), implode(", ", $formatsAvailable)));
    }

}