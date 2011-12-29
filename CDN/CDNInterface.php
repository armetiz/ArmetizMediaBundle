<?php

namespace Leezy\MediaBundle\CDN;

interface CDNInterface
{
    public function getPath ($relativePath);
}