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

    public function getMedia(MediaInterface $media, $format, array $options = array())
    {
        if(array_key_exists("template", $options)) {
            $templateName = $options["template"];
        }
        else {
            $templateName = "default";
        }
        
        $template = $this->getMediaManager()->getTemplate($media, $templateName);
        
        if (empty($template)) {
            throw new NoTemplateException($media);
        }
        
        $uri = $this->getMediaManager()->getUri($media, $format);
        $options = $this->getMediaManager()->getRenderOptions($media, $format);
        
        return $this->getTemplating()->render($template, array(
            'media' => $media,
            'format' => $format,
            'options' => $options,
            'uri' => $uri,
        ));
    }

    public function getUri(MediaInterface $media, $format)
    {        
        return $this->getMediaManager()->getUri($media, $format);
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