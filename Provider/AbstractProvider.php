<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\CDN\CDNInterface;
use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\Generator\Path\DefaultPathGenerator;

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
    private $templates;
    
    /**
     * @var array
     */
    private $formats;
    
    /**
     * @var
     */
    private $pathGenerator;
    
    public function __construct ()
    {
        $this->templates = array();
        $this->formats = array();
        $this->pathGenerator = new DefaultPathGenerator();
    }
    
    abstract function generateFormats(MediaInterface $media);
    
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
    
    public function setPathGenerator (PathGeneratorInterface $value)
    {
        $this->pathGenerator = $value;
        
        return $this;
    }
    
    public function getPathGenerator ()
    {
        return $this->pathGenerator;
    }
    
    final public function setFormats (array $formats)
    {
        foreach ($formats as $format) {
            $format->getTransformer()->setPathGenerator($this->getPathGenerator());
        }
        
        $this->formats = $formats;
        
        return $this;
    }
    
    final public function getFormats ()
    {
        return $this->formats;
    }
    
    final public function hasFormat($name)
    {
        foreach($this->getFormats() as $format) {
            if ($name === $format->getName()) {
                return true;
            }
        }
        
        return false;
    }
    
    final public function getFormat($name)
    {
        foreach($this->getFormats() as $format) {
            if ($name === $format->getName()) {
                return $format;
            }
        }
        
        return null;
    }
    
    public function getRenderOptions (MediaInterface $media, $format, array $options = array())
    {
        $format = $this->getFormat($format);
        
        if ($format) {
            return $format->getTransformer()->getRenderOptions($media, $options);
        }
        
        return array();
    }
    
    final public function getTemplate ($formatName)
    {
        if (null === $formatName) {
            return $this->getDefaultTemplate();
        }
        
        $format = $this->getFormat($formatName);
        
        if ($format) {
            return $format->getTransformer()->getTemplate();
        }
        
        return array();
    }
    
    public function getDefaultTemplate()
    {
        return null;
    }
}
