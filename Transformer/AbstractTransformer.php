<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Transformer\TransformerInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Format;

use Gaufrette\Filesystem;

abstract class AbstractTransformer implements TransformerInterface
{
    private $pathGenerator;
    private $filesystem;
    
    abstract public function delete(MediaInterface $media, Format $format);
    
    abstract public function create(MediaInterface $media, Format $format);
    
    abstract public function getTemplate();
    
    abstract public function getRenderOptions(MediaInterface $media, Format $format);
    
    abstract public function getName();
    
    public function setFilesystem(Filesystem $value)
    {
        $this->filesystem = $value;
        
        return $this;
    }
    
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    public function setPathGenerator(PathGeneratorInterface $value)
    {
        $this->pathGenerator = $value;
        
        return $this;
    }
    
    public function getPathGenerator()
    {
        return $this->pathGenerator;
    }
    
    public function getPath(MediaInterface $media, Format $format)
    {
        if(null === $this->getPathGenerator()) {
            throw new \RuntimeException("Path transformer not defined");
        }
        
        return $this->getPathGenerator()->getPath($media, $format);
    }
}