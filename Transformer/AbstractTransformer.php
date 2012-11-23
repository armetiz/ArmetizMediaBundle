<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Transformer\TransformerInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;

use Gaufrette\File;

abstract class AbstractTransformer implements TransformerInterface
{
    private $pathGenerator;
    
    abstract public function delete(MediaInterface $media);
    
    abstract public function create(MediaInterface $media, array $options = array(), File $fileOrigin = null);
    
    abstract public function getTemplate();
    
    abstract public function getRenderOptions(MediaInterface $media, array $options = array());
    
    abstract public function getName();
    
    public function setPathGenerator(PathGeneratorInterface $value)
    {
        $this->pathGenerator = $value;
        
        return $this;
    }
    
    public function getPathGenerator()
    {
        return $this->pathGenerator;
    }
    
    public function getPath(MediaInterface $media)
    {
        if(null === $this->getPathGenerator()) {
            throw new \RuntimeException("Path transformer not defined");
        }
        
        return $this->getPathGenerator()->getPath($media, $this->getName());
    }
}