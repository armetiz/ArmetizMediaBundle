<?php

namespace Leezy\MediaBundle\Generator;

use Leezy\MediaBundle\Models\MediaInterface;

interface PathInterface
{
    public function generatePath (MediaInterface $media);
}