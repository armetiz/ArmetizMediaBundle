<?php

namespace Armetiz\MediaBundle\Generator\Path;

use Armetiz\MediaBundle\HttpFoundation\File\MimeType\ExtensionGuesser;
use Armetiz\MediaBundle\Exceptions\UnknowMimeTypeException;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Format;

/**
 * Chemin d'un media/format au sein de son container
 */
class DefaultPathGenerator extends AbstractPathGenerator
{
    public function getPath(MediaInterface $media, Format $format = null)
    {
        $this->flush();
        
        if($format) {
            $mimeType = $format->getTransformer()->getMimeType();
            
            $options = $format->getOptions();
            $this->addPath($options["namespace"]);
        }
        else {
            $mimeType = $media->getMimeType();
            
            $this->addPath("original");
        }
        
        $this->addPath($media->getDateCreation()->format("Y-m"));
        
        $extension = ExtensionGuesser::guess($mimeType);
        
        if (!$extension) {
            throw new UnknowMimeTypeException($mimeType);
        }
        
        $this->addPath($media->getMediaIdentifier() . "." . $extension);
        
        return $this->getFinalPath();
    }
    
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }
}