<?php

namespace Leezy\MediaBundle\CDN;

class Server implements CDNInterface
{
    protected $path;
    
    public function __construct ($path)
    {
        $this->path = $path;
    }
    
    public function getPath ($relativePath)
    {
        return sprintf('%s/%s', $this->path, $relativePath);
    }
}