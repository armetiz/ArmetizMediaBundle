<?php

namespace Armetiz\MediaBundle\Generator\Path;

use Armetiz\MediaBundle\Entity\MediaInterface;

interface PathGeneratorInterface
{
    public function getPath(MediaInterface $media, $format, array $options = array());
}