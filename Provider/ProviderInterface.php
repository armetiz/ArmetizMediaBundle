<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\CDN\CDNInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Format;

use Gaufrette\Filesystem;

interface ProviderInterface
{
    public function saveMedia (MediaInterface $media);    
    public function deleteMedia (MediaInterface $media);
    public function prepareMedia (MediaInterface $media);
    public function canHandleMedia (MediaInterface $media);
    
    public function setFormats(array $formats);
    public function getFormats();
    public function getFormat(Format $format);
    public function hasFormat(Format $format);

    public function getUri (MediaInterface $media, Format $format = null);
    public function getRenderOptions (MediaInterface $media, Format $format = null);
    
    public function getTemplate (Format $format = null);
    
    public function setFilesystem(Filesystem $value);
    public function getFilesystem();
    
    public function setContentDeliveryNetwork (CDNInterface $value);
    public function getContentDeliveryNetwork ();
    
    public function setPathGenerator (PathGeneratorInterface $value);
    public function getPathGenerator ();
}
