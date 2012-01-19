<?php

namespace Leezy\MediaBundle\Provider;

use Leezy\MediaBundle\Models\MediaInterface;
use Leezy\MediaBundle\CDN\CDNInterface;

interface ProviderInterface
{
    public function saveMedia (MediaInterface $media);    
    public function deleteMedia (MediaInterface $media);
    public function prepareMedia (MediaInterface $media);
}
