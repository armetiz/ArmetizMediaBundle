<?php

namespace Armetiz\MediaBundle\Generator\Format;

use Armetiz\MediaBundle\Generator\Format\FormatGeneratorInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;

use Gaufrette\Filesystem;
use Gaufrette\File;

abstract class AbstractTransformer implements FormatGeneratorInterface
{
    private $name;
    private $filesystem;
    private $pathGenerator;
    
    abstract public function delete(MediaInterface $media);
    
    abstract public function create(MediaInterface $media, array $options = array(), File $fileOrigin = null);
    
    abstract public function getTemplate();
    
    abstract public function getRenderOptions(MediaInterface $media, array $options = array());
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
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
}