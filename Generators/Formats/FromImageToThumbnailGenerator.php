<?php

namespace Armetiz\MediaBundle\Generators\Formats;

use Armetiz\MediaBundle\Generators\FormatGeneratorInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

use Imagine\Image\ImageInterface;
use Imagine\Image\Box;

use Gaufrette\File;

class FromImageToThumbnailGenerator implements FormatGeneratorInterface
{
    const RESIZE_MODE_OUTBOUND = 'outbound';
    const RESIZE_MODE_INSET = 'inset';
    
    private $imagine;
    
    public function create(MediaInterface $media, array $options = array(), File $fileTarget = null, File $fileOrigin = null)
    {
        switch ($options["engine_image"]) {
            case "gd":
                $imagine = new \Imagine\Gd\Imagine();
                break;
            case "imagick":
                $imagine = new \Imagine\Imagick\Imagine();
                break;
            case "gmagick":
                $imagine = new \Imagine\Gmagick\Imagine();
                break;
            default:
                throw new \RuntimeException("Use one of the following 'engine_image' : 'gd', 'imagick', 'gmagick'");
        }
        
        if (!array_key_exists('quality', $options)) {
            $options['quality'] = 100;
        }

        $mode = isset($options['mode']) ? $options['mode'] : self::RESIZE_MODE_OUTBOUND;
        $width = isset($options['width']) ? (int)$options['width'] : null;
        $height = isset($options['height']) ? (int)$options['height'] : null;

        if (!is_numeric($width) && !is_numeric($height)) {
            throw new \InvalidArgumentException('You must specify at least a width and/or an height value');
        }
        
        $image = $imagine->load($fileOrigin->getContent());

        if (null == $width) {
            $image = $image->resize($image->getSize()->heighten($height));
        } elseif (null == $height) {
            $image = $image->resize($image->getSize()->widen($width));
        } else {
            switch($mode) {
                case self::RESIZE_MODE_OUTBOUND:
                    $mode = ImageInterface::THUMBNAIL_OUTBOUND;
                break;

                case self::RESIZE_MODE_INSET:
                    $mode = ImageInterface::THUMBNAIL_INSET;
                break;

                default:
                    $mode = ImageInterface::THUMBNAIL_OUTBOUND;
            }

            $image = $image->thumbnail(new Box($width, $height), $mode);
        }

        $outputContent = $image->get(ExtensionGuesser::getInstance()->guess($media->getContentType()), $options);
        $fileTarget->setContent($outputContent);
    }
}