<?php

namespace Leezy\MediaBundle\Models;

interface MediaInterface
{
    /**
     * Use to store the File or the path to temporary file.
     */
    public function getMedia();
    public function setMedia($value);

    /**
     * Use to store the name of the file.
     */
    public function getMediaIdentifier();
    public function setMediaIdentifier($value);
}