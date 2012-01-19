<?php

namespace Leezy\MediaBundle\Provider;

use Leezy\MediaBundle\Models\MediaInterface;
use Leezy\MediaBundle\Exceptions\NotValidException;

use Imagine\Gd\Imagine;

class ImageProvider extends FileProvider
{
    public function validate (MediaInterface $media) {
        $path = $media->getMedia()->getRealPath();
        
        $imagine = new Imagine();
        $image = $imagine->open($path);
        $size = $image->getSize ();
        
        $format = $this->getFormat ($media->getFormat());  
        
        if (array_key_exists("width", $format)) {
            $width = $format["width"];
            
            if ($width != $size->getWidth())
                throw new NotValidException("width", $width, $size->getWidth());
        }
        
        if (array_key_exists("height", $format)) {
            $width = $format["height"];
            
            if ($width != $size->getWidth())
                throw new NotValidException("height", $width, $size->getWidth());
        }
    }
}