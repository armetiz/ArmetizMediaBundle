<?php

namespace Armetiz\MediaBundle\Twig\Extension;

use Armetiz\MediaBundle\Templating\Helper\MediaHelper;
use Armetiz\MediaBundle\Entity\MediaInterface;

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
        );
    }
    
    public function getMedia(MediaInterface $media, $formatName = "default")
    {
        return $this->helper->getMedia($media, $formatName);
    }

    public function getUri(MediaInterface $media, $formatName = null)
    {
        return $this->helper->getUri($media, $formatName);
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