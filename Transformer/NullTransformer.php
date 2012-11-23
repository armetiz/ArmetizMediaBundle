<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Entity\MediaInterface;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

use Imagine\Image\ImageInterface;
use Imagine\Image\Box;

use Gaufrette\File;

class NullTransformer extends AbstractTransformer
{
    public function getName()
    {
        return "null";
    }
    
    public function getTemplate()
    {
        return null;
    }
    
    public function getRenderOptions(MediaInterface $media, array $options = array())
    {
        return array();
    }
    
    public function delete(MediaInterface $media)
    {
        return $this;
    }
    
    public function create(MediaInterface $media, array $options = array(), File $fileOrigin = null)
    {
        return $this;
    }
}