<?php

namespace Armetiz\MediaBundle\Generator\Path;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Format;

interface PathGeneratorInterface
{
    public function getPath(MediaInterface $media, Format $format = null);
}