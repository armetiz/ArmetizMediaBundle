<?php

namespace Armetiz\MediaBundle\Generators;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Gaufrette\File;

interface FormatGeneratorInterface
{
    public function create(MediaInterface $media, array $options = array(), File $fileTarget = null, File $fileOrigin = null);
}