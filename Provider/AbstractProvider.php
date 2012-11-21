<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\CDN\CDNInterface;

use Gaufrette\Filesystem;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var Armetiz\MediaBundle\Filesystem
     */
    private $filesystem;
    
    /**
     * @var Armetiz\MediaBundle\CDN\CDNInterface
     */
    private $contentDeliveryNetwork;
    
    /**
     * @var string
     */
    private $namespace;
    
    /**
     * @var string
     */
    private $templates;
    
    /**
     * @var array
     */
    private $formats;
    
    public function __construct ()
    {
        $this->templates = array();
    }
    
    abstract public function saveMedia (MediaInterface $media);
    
    abstract public function deleteMedia (MediaInterface $media);
    
    public function prepareMedia (MediaInterface $media) {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
        
        if (null === $media->getMeta()) {
            $media->setMeta(array());
        }
        
        return $this;
    }
    
    abstract public function getUri (MediaInterface $media, $format);
    
    public function setFilesystem(Filesystem $value)
    {
        $this->filesystem = $value;
        
        return $this;
    }
    
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    public function setContentDeliveryNetwork (CDNInterface $value)
    {
        $this->contentDeliveryNetwork = $value;
        
        return $this;
    }
    
    public function getContentDeliveryNetwork ()
    {
        return $this->contentDeliveryNetwork;
    }
    
    public function setNamespace($value)
    {
        $this->namespace = $value;
        
        return $this;
    }
    
    public function getNamespace ()
    {      
        return $this->namespace;
    }
    
    public function setTemplates(array $value)
    {
        $this->templates = $value;
        
        return $this;
    }
    
    public function getTemplates()
    {
        return $this->templates;
    }
    
    public function getTemplate ($name = "default")
    {
        $templates = $this->getTemplates();
        
        if(array_key_exists($name, $templates)) {
            return $templates[$name];
        }
        elseif (array_key_exists("default", $templates)) {
            return $templates["default"];
        }
        else {
            return $this->getDefaultTemplate();
        }
    }
    
    public function setFormats (array $value)
    {
        $this->formats = $value;
        
        return $this;
    }
    
    public function getFormats ()
    {
        return $this->formats;
    }
    
    public function getDefaultFormats()
    {
        return array();
    }
    
    public function getRenderOptions (MediaInterface $media, $format, array $options = array())
    {
        return array();
    }
    
    public function getDefaultTemplate() {
        return null;
    }
}
