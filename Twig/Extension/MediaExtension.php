<?php

namespace Leezy\MediaBundle\Twig\Extension;

use Leezy\MediaBundle\Templating\Helper\MediaHelper;
use Leezy\MediaBundle\Models\MediaInterface;

/**
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class MediaExtension extends \Twig_Extension
{
    /**
     * @var MediaHelper
     */
    protected $helper;

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
            'mediaPath' => new \Twig_Function_Method($this, 'getPath'),
        );
    }
    
    public function getFilters()
    {
        return array(
            'mediaRaw' => new \Twig_Filter_Method($this, 'getRaw'),
        );
    }

    public function getMedia($media, $format = null, array $options = array())
    {
        if ( !($media instanceof MediaInterface))
            return "";
        
        return $this->helper->getMedia($media, $format, $options);
    }

    public function getPath($media, $format = null)
    {
        if ( !($media instanceof MediaInterface))
            return "";
        
        return $this->helper->getUri($media, $format);
    }

    public function getRaw($media, $format = null)
    {
        if ( !($media instanceof MediaInterface))
            return "";
        
        return $this->helper->getRaw($media, $format);
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