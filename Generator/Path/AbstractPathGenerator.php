<?php

namespace Armetiz\MediaBundle\Generator\Path;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Format;

/**
 * Chemin d'un media/format au sein de son container
 */
abstract class AbstractPathGenerator implements PathGeneratorInterface
{
    private $path;
    
    abstract public function getPath(MediaInterface $media, Format $format = null);
    
    final protected function addPath($value)
    {
        if (!is_string($value)) {
            throw new \RuntimeException("Add path value have to be a string");
        }
        
        if(null === $this->path) {
            $this->path = $value;
        }
        else {
            $this->path = $this->path . "/" . $value;
        }
        
        return $this;
    }
    
    final protected function flush()
    {
        $this->path = null;
    }
    
    final protected function getFinalPath()
    {
        return $this->path;
    }
}