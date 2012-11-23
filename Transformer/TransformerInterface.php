<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;

use Gaufrette\File;

interface TransformerInterface
{
    public function getName();
    public function getTemplate();
    public function getRenderOptions(MediaInterface $media, array $options = array());
    
    public function setPathGenerator(PathGeneratorInterface $value);
    public function getPathGenerator();
    public function getPath(MediaInterface $media);
    
    public function create(MediaInterface $media, array $options = array(), File $fileOrigin = null);
    public function delete(MediaInterface $media);
}