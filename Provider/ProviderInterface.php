<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

interface ProviderInterface
{
    public function saveMedia (MediaInterface $media);    
    public function deleteMedia (MediaInterface $media);
    public function prepareMedia (MediaInterface $media);
    public function canHandleMedia (MediaInterface $media);
}
