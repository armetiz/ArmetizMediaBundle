<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;

use Gaufrette\Filesystem;
use Gaufrette\File;

interface FormatGeneratorInterface
{
    public function getName();
    public function setName($name);
    public function getTemplate();
    public function getRenderOptions(MediaInterface $media, array $options = array());
    
    public function setFilesystem(Filesystem $value);
    public function getFilesystem();
    
    public function setPathGenerator(PathGeneratorInterface $value);
    public function getPathGenerator();
    
    public function create(MediaInterface $media, array $options = array(), File $fileOrigin = null);
    public function delete(MediaInterface $media);
}