<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\Format;

use Gaufrette\Filesystem;

interface TransformerInterface
{
    public function getName();
    public function getTemplate();
    public function getRenderOptions(MediaInterface $media, Format $format);
    
    public function setFilesystem(Filesystem $value);
    public function getFilesystem();
    
    public function setPathGenerator(PathGeneratorInterface $value);
    public function getPathGenerator();
    public function getPath(MediaInterface $media, Format $format);
    
    public function create(MediaInterface $media, Format $format);
    public function delete(MediaInterface $media, Format $format);
}