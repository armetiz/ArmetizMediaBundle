<?php

namespace Leezy\MediaBundle\Models;

interface MediaAdvancedInterface extends MediaInterface
{
    const STATUS_OK          = 1;
    const STATUS_SENDING     = 2;
    const STATUS_PENDING     = 3;
    const STATUS_ERROR       = 4;
    const STATUS_ENCODING    = 5;
    
    public function getContentType();
    public function setContentType($value);
    
    public function getProviderStatus();
    public function setProviderStatus($value);
    
    public function getContext();
    public function setContext($value);
}