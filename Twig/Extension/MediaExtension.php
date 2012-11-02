<?php

namespace Armetiz\MediaBundle\Twig\Extension;

use Armetiz\MediaBundle\Templating\Helper\MediaHelper;

/**
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class MediaExtension extends \Twig_Extension
{
    /**
     * @var MediaHelper
     */
    private $helper;

    public function __construct(MediaHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'media' => new \Twig_Function_Method($this, 'getMedia', array('is_safe' => array('html'))),
            'mediaUri' => new \Twig_Function_Method($this, 'getUri'),
            'mediaRaw' => new \Twig_Function_Method($this, 'getRaw'),
            'mediaPath' => new \Twig_Function_Method($this, 'getPath'),
        );
    }
    
    public function getMedia($value, $format = null, array $options = array())
    {
        $media = $this->findMedia ($value, $format);
        
        if (!$media)
            return '';
        
        return $this->helper->getMedia($media, $format, $options);
    }

    public function getUri($value, $format = null)
    {
        
        $media = $this->findMedia ($value, $format);
        
        if (!$media)
            return '';
        
        return $this->helper->getUri($media, $format);
    }
    
    public function getPath($value, $format = null)
    {
        
        $media = $this->findMedia ($value, $format);
        
        if (!$media)
            return '';
        
        return $this->helper->getPath($media, $format);
    }

    public function getRaw($value, $format = null)
    {
        $media = $this->findMedia ($value, $format);
        
        if (!$media)
            return '';
        
        return $this->helper->getRaw($media, $format);
    }
    
    private function findMedia ($value, $format = null) {
        return $this->helper->getMediaManager ()->findMedia($value, $format);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'media';
    }
}