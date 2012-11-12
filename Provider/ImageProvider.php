<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\HttpFoundation\File\MimeType\ExtensionGuesser;
use Armetiz\MediaBundle\Provider\FileProvider;

use Symfony\Component\HttpFoundation\File\File;

class ImageProvider extends FileProvider
{
    public function canHandleMedia (MediaInterface $media)
    {
        $extension = ExtensionGuesser::guess($media->getContentType());
        
        return ((null !== $extension) || $media->getMedia() && ($media->getMedia() instanceof File || is_file($media->getMedia())));
    }
    
    public function getDefaultTemplate() {
        return "ArmetizMediaBundle:Image:default.html.twig";
    }
}