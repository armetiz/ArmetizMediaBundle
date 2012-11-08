<?php

namespace Armetiz\MediaBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Exceptions\NoTemplateException;

class MediaHelper extends Helper
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getMedia(MediaInterface $media, $format = null, array $options = array())
    {
        $template = $this->getMediaManager()->getTemplate($media);
        
        if (empty($template)) {
            throw new NoTemplateException($media);
        }
        
        $uri = $this->getMediaManager()->getUri($media);
        $raw = $this->getMediaManager()->getRaw($media);
        
        return $this->getTemplating()->render($template, array(
            'media' => $media,
            'format' => $format,
            'options' => $options,
            'raw' => $raw,
            'uri' => $uri
        ));
    }

    public function getUri(MediaInterface $media, $format = null)
    {        
        return $this->getMediaManager()->getUri($media);
    }

    public function getPath(MediaInterface $media, $format = null)
    {        
        return $this->getMediaManager()->getPath($media);
    }

    public function getRaw(MediaInterface $media, $format = null)
    {        
        return $this->getMediaManager()->getRaw($media);
    }
    
    public function getMediaManager ()
    {
        return $this->container->get('armetiz.media.manager');
    }
    
    public function getTemplating ()
    {
        return $this->container->get('templating');
    }

    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return 'media';
    }

}