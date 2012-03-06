<?php

namespace Leezy\MediaBundle\Entity;

interface FormatInterface
{
    /**
     * Format of media
     */
    public function getFormat();
    public function setFormat($value);
}