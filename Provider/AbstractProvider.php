<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Exceptions\NotSupportedFormatException;
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
    private $template;
    
    /**
     * @var string
     */
    private $formats;
    
    public function __construct ()
    {
        $this->formats = array();
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
    
    public function setTemplate($value)
    {
        $this->template = $value;
    }
    
    public function getTemplate ()
    {      
        return $this->template;
    }
    
    public function setFormats ($value) {
        $this->formats = $value;
    }
    
    public function getFormats () {
        return $this->formats;
    }
    
    public function getFormat ($format) {
        if (!array_key_exists($format, $this->formats)) {
            throw new NotSupportedFormatException ($format);
        }
        
        return $this->formats[$format];
    }
}
