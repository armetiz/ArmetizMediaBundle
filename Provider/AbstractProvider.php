<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Format;
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
    
    abstract public function getUri (MediaInterface $media, Format $format = null);
    
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
            $format->getTransformer()->setFilesystem($this->getFilesystem());
            $format->getTransformer()->setPathGenerator($this->getPathGenerator());
        }
        
        $this->formats = $formats;
        
        return $this;
    }
    
    final public function getFormats ()
    {
        return $this->formats;
    }
    
    final public function hasFormat(Format $format)
    {
        foreach($this->getFormats() as $formatAvailable) {
            if ($formatAvailable === $format) {
                return true;
            }
        }
        
        return false;
    }
    
    final public function getFormat(Format $format)
    {
        foreach($this->getFormats() as $formatAvailable) {
            if ($formatAvailable === $format) {
                return $format;
            }
        }
        
        return null;
    }
    
    public function getRenderOptions (MediaInterface $media, Format $format = null)
    {
        if ($format) {
            return $format->getTransformer()->getRenderOptions($media, $format);
        }
        
        throw array();
    }
    
    final public function getTemplate (Format $format = null)
    {
        if (null === $format) {
            return $this->getDefaultTemplate();
        }
        else {        
            return $format->getTransformer()->getTemplate();
        }
    }
    
    public function getDefaultTemplate()
    {
        return null;
    }
}
