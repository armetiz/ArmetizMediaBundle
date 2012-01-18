<?php

namespace Leezy\MediaBundle\Models;

interface FormatInterface
{
    /**
     * Format of media
     */
    public function getFormat();
    public function setFormat($value);
}