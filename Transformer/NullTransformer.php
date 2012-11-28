<?php

namespace Armetiz\MediaBundle\Transformer;

use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Format;

class NullTransformer extends AbstractTransformer
{
    public function getName()
    {
        return "null";
    }
    
    public function getTemplate()
    {
        return "ArmetizMediaBundle:Null:default.html.twig";
    }
    
    public function getRenderOptions(MediaInterface $media, Format $format)
    {
        return array();
    }
    
    public function delete(MediaInterface $media, Format $format)
    {
        return $this;
    }
    
    public function create(MediaInterface $media, Format $format)
    {
        return $this;
    }
}