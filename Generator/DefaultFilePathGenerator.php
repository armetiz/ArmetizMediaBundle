<?php

namespace Leezy\MediaBundle\Generator;

use Leezy\MediaBundle\Models\MediaInterface;
use Symfony\Component\HttpFoundation\File\File;

class DefaultFilePathGenerator implements PathInterface
{
    /**
     *
     * @var string
     */
    protected $namespace;
    
    public function __construct ($namespace = null)
    {
        $this->namespace = $namespace;
    }
    
    public function generatePath (MediaInterface $media)
    {
        $path = $media->getMediaIdentifier();
        
        if ($this->namespace)
            return sprintf ("%s/%s", $this->namespace, $path);
        else
            return $path;
    }
}