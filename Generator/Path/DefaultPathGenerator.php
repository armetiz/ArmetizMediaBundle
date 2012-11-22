<?php

namespace Armetiz\MediaBundle\Generator\Path;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Entity\MediaTimestampableInterface;

class DefaultPathGenerator implements PathGeneratorInterface
{
    public function getPath(MediaInterface $media, $format, array $options = array())
    {
        $path = "";
        
        if ($media instanceof MediaTimestampableInterface) {
            $dateCreation = $media->getDateCreation();
            $path = $path . "/" . $dateCreation->format("Y-m");
        }
        
        $pathInfo = pathinfo($media->getMediaIdentifier());
        
        if ($format) {
            return $path . "/" . $pathInfo["filename"] . "_" . $format . "." . $pathInfo["extension"];
        }
        else {
            return $path . "/" . $pathInfo["filename"] . "." . $pathInfo["extension"];
        }
    }
}