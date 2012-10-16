<?php

namespace Armetiz\MediaBundle\CDN;

interface CDNInterface
{
    public function getPath ($relativePath);
}