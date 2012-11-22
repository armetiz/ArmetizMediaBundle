<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Generator\Path\PathGeneratorInterface;
use Armetiz\MediaBundle\CDN\CDNInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;

use Gaufrette\Filesystem;

interface ProviderInterface
{
    public function saveMedia (MediaInterface $media);    
    public function deleteMedia (MediaInterface $media);
    public function prepareMedia (MediaInterface $media);
    public function canHandleMedia (MediaInterface $media);
    
    public function setFormats(array $formats);
    public function getFormats();
    public function getFormat($name);
    public function hasFormat($name);

    public function getUri (MediaInterface $media, $format);
    public function getRenderOptions (MediaInterface $media, $format, array $options = array());
    
    public function getTemplate ($format);
    
    public function setFilesystem(Filesystem $value);
    public function getFilesystem();
    
    public function setContentDeliveryNetwork (CDNInterface $value);
    public function getContentDeliveryNetwork ();
    
    public function setPathGenerator (PathGeneratorInterface $value);
    public function getPathGenerator ();
}
