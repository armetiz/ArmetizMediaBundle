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

    public function getMedia(MediaInterface $media, $formatName)
    {
        $template = $this->getMediaManager()->getTemplate($media, $formatName);
        
        if (empty($template)) {
            throw new NoTemplateException($media, $formatName);
        }
        
        $uri = $this->getMediaManager()->getUri($media, $formatName);
        $options = $this->getMediaManager()->getRenderOptions($media, $formatName);
        
        return $this->getTemplating()->render($template, array(
            'media' => $media,
            'format' => $formatName,
            'options' => $options,
            'uri' => $uri,
        ));
    }

    public function getUri(MediaInterface $media, $formatName)
    {        
        return $this->getMediaManager()->getUri($media, $formatName);
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