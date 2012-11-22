<?php

namespace Armetiz\MediaBundle;

class Format 
{
    private $name;
    private $generator;
    private $options;
    
    public function __construct ($name, $generator, array $options = array())
    {
        $this->name = $name;
        //TODO !
        $this->generator = new \Armetiz\MediaBundle\Generator\Format\FromImageToThumbnailGenerator();
        $this->options = $options;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getGenerator()
    {
        return $this->generator;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
}