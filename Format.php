<?php

namespace Armetiz\MediaBundle;

use Armetiz\MediaBundle\Transformer\TransformerInterface;

class Format 
{
    private $name;
    private $transformer;
    private $options;
    
    public function __construct ($name, TransformerInterface $transformer, array $options = array())
    {
        $this->name = $name;
        $this->transformer = $transformer;
        $this->options = $options;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getTransformer()
    {
        return $this->transformer;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
}