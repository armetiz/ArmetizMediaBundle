<?php

namespace Leezy\MediaBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Leezy\MediaBundle\Generator\PathInterface;
use Leezy\MediaBundle\Models\MediaInterface;
use Leezy\MediaBundle\CDN\CDNInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     *
     * @var Leezy\MediaBundle\Filesystem
     */
    protected $filesystem;
    
    /**
     * @var Leezy\MediaBundle\CDN\CDNInterface
     */
    protected $contentDeliveryNetwork;
    
    /**
     *
     * @var Leezy\MediaBundle\Generator\PathInterface
     */
    protected $pathGenerator;
    
    /**
     * @var string
     */
    protected $namespace;
    
    /**
     *
     * @var string
     */
    protected $template;
    
    /**
     *
     * @var string
     */
    protected $formats;
    
    public function __construct ($filesystem, CDNInterface $contentDeliveryNetwork, $pathGenerator, $namespace = null, $template = null, $formats = null)
    {
        $this->filesystem       = $filesystem;
        $this->namespace        = $namespace;
        $this->formats          = $formats;
        $this->template         = $template;
        $this->contentDeliveryNetwork = $contentDeliveryNetwork;
        
        if ($pathGenerator instanceof PathInterface)
            $this->pathGenerator = $pathGenerator;
        elseif (is_string($pathGenerator))
            $this->pathGenerator = new $pathGenerator($namespace);
        else
            throw new \Exception("A path generator is mandatory");
    }
    
    public function saveMedia (MediaInterface $media)
    {
        
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        
    }
    
    public function prepareMedia (MediaInterface $media, $format = null)
    {
        
    }
    
    public function getUri (MediaInterface $media)
    {
        $path = $this->getPathGenerator()->generatePath ($media);
        
        return $this->getContentDeliveryNetwork()->getPath($path);
    }
    
    public function getPath (MediaInterface $media)
    {
        $path = $this->getPathGenerator()->generatePath ($media);
        
        throw new \Exception ("Implementation recquire");
    }
    
    public function getTemplate ()
    {      
        return $this->template;
    }
    
    public function getRaw (MediaInterface $media)
    {
        return null;
    }
    
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    public function getPathGenerator ()
    {
        return $this->pathGenerator;
    }
    
    public function getContentDeliveryNetwork ()
    {
        return $this->contentDeliveryNetwork;
    }
}
