<?php

namespace Armetiz\MediaBundle\Entity;

interface FormatInterface
{
    /**
     * Format of media
     */
    public function getFormat();
    public function setFormat($value);
}