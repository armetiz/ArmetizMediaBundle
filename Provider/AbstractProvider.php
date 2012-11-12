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
    }
    
    abstract public function getRaw (MediaInterface $media);
    
    abstract public function getUri (MediaInterface $media);
    
    abstract public function getPath (MediaInterface $media);
    
    public function setFilesystem(Filesystem $value)
    {
        $this->filesystem = $value;
    }
    
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    public function setContentDeliveryNetwork (CDNInterface $value)
    {
        $this->contentDeliveryNetwork = $value;
    }
    
    public function getContentDeliveryNetwork ()
    {
        return $this->contentDeliveryNetwork;
    }
    
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }
    
    public function getNamespace ()
    {      
        return $this->namespace;
    }
    
    public function setTemplates(array $value)
    {
        $this->templates = $value;
    }
    
    public function getTemplates()
    {
        return $this->templates;
    }
    
    public function getTemplate ($name = "default")
    {
        if(array_key_exists($name, $this->templates)) {
            return $this->templates[$name];
        }
        else {
            return $this->getDefaultTemplate();
        }
    }
    
    public function getDefaultTemplate() {
        return null;
    }
}
