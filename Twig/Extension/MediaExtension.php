<?php

namespace Leezy\MediaBundle\Twig\Extension;

use Doctrine\Common\Collections\Collection;

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
            'mediaPath' => new \Twig_Function_Method($this, 'getPath'),
        );
    }
    
    public function getFilters()
    {
        return array(
            'mediaRaw' => new \Twig_Filter_Method($this, 'getRaw'),
        );
    }

    public function getMedia($value, $format = null, array $options = array())
    {
        if (is_array($value)) {
            foreach ($value as $media) {
                if ($format == $media->getFormat()) break;
            }
        }
        
        if ( !($media instanceof MediaInterface))
            return "";
        
        return $this->helper->getMedia($media, $format, $options);
    }

    public function getPath($value, $format = null)
    {
        $media = $this->findMedia ($value, $format);
        
        if (!$media)
            return '';
        
        return $this->helper->getUri($media, $format);
    }

    public function getRaw($media, $format = null)
    {
        $media = $this->findMedia ($value, $format);
        
        if (!$media)
            return '';
        
        return $this->helper->getRaw($media, $format);
    }
    
    private function findMedia ($value, $format = null) {
        $media = null;
        
        if ($value instanceof Collection) {
            foreach ($value as $mediaBlack) {
                if ($format == $mediaBlack->getFormat()) {
                    $media = $mediaBlack;
                    break;
                }
            }
        }
        
        if ( !($media instanceof MediaInterface))
            return null;
        
        return $media;
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