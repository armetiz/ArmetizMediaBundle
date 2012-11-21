<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

interface ProviderInterface
{
    public function saveMedia (MediaInterface $media);    
    public function deleteMedia (MediaInterface $media);
    public function prepareMedia (MediaInterface $media);
    public function canHandleMedia (MediaInterface $media);
    
    public function setFormats (array $formats);
    public function getFormats ();
    public function getDefaultFormats();

    public function getUri (MediaInterface $media, $format);
    public function getRenderOptions (MediaInterface $media, $format, array $options = array());
    
    public function setTemplates (array $value);
    public function getTemplates ();
    public function getTemplate ($name = "default");
    public function getDefaultTemplate ();
}
