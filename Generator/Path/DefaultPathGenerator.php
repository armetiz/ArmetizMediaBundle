<?php

namespace Armetiz\MediaBundle\Generator\Path;

use Armetiz\MediaBundle\Entity\MediaInterface;

class DefaultPathGenerator implements PathGeneratorInterface
{
    public function getPath(MediaInterface $media, $format, array $options = array())
    {
        $path = "";
        
        if (array_key_exists("namespace", $options)) {
            $path .= $options["namespace"] . "/";
        }
        
        $dateCreation = $media->getDateCreation();
        $path .= $dateCreation->format("Y-m") . "/";
        
        $pathInfo = pathinfo($media->getMediaIdentifier());
        
        if ($format) {
            return $path . $pathInfo["filename"] . "_" . $format . "." . $pathInfo["extension"];
        }
        else {
            return $path . $pathInfo["filename"] . "." . $pathInfo["extension"];
        }
    }
}