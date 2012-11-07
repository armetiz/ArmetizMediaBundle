<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

interface ProviderInterface
{
    public function saveMedia (MediaInterface $media);    
    public function deleteMedia (MediaInterface $media);
    public function prepareMedia (MediaInterface $media);
    public function canHandleMedia (MediaInterface $media);
    
    public function getRaw (MediaInterface $media);
    public function getUri (MediaInterface $media);
    public function getPath (MediaInterface $media);
}
