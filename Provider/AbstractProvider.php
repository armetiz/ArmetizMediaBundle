<?php

namespace Leezy\MediaBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Leezy\MediaBundle\Exceptions\NotSupportedFormatException;
use Leezy\MediaBundle\Models\FormatInterface;
use Leezy\MediaBundle\Models\MediaInterface;
use Leezy\MediaBundle\CDN\CDNInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     *
     * @var Leezy\MediaBundle\Filesystem
     */
    private $filesystem;
    
    /**
     * @var Leezy\MediaBundle\CDN\CDNInterface
     */
    private $contentDeliveryNetwork;
    
    /**
     * @var string
     */
    private $namespace;
    
    /**
     *
     * @var string
     */
    private $template;
    
    /**
     *
     * @var string
     */
    private $formats;
    
    public function __construct ($filesystem, CDNInterface $contentDeliveryNetwork, $namespace, $template = null)
    {
        $this->filesystem = $filesystem;
        $this->namespace = $namespace;
        $this->template = $template;
        $this->contentDeliveryNetwork = $contentDeliveryNetwork;
        $this->formats = null;
    }
    
    public function saveMedia (MediaInterface $media)
    {
        
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        
    }
    
    public function setFormats ($value) {
        $this->formats = $value;
    }
    
    public function getFormats () {
        return $this->formats;
    }
    
    public function getUri (MediaInterface $media)
    {
        $path = $this->getPath ($media);
        
        return $this->getContentDeliveryNetwork()->getPath($path);
    }
    
    public function getFormat ($format) {
        if (!array_key_exists($format, $this->formats)) {
            throw new NotSupportedFormatException ($format);
        }
        
        return $this->formats[$format];
    }
    
    public function getPath (MediaInterface $media)
    {
        $path = $media->getMediaIdentifier();
        
        if ($media instanceof FormatInterface) {
            $format = $this->getFormat ($media->getFormat());            
            $folder = $format["folder"];
            
            return sprintf ("%s/%s/%s", $this->namespace, $folder, $path);
        }
        
        return sprintf ("%s/%s", $this->namespace, $path);
        
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
    
    public function getContentDeliveryNetwork ()
    {
        return $this->contentDeliveryNetwork;
    }
}
