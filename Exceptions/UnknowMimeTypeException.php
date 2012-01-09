<?php

namespace Leezy\MediaBundle\Exceptions;

use Exception;

class UnknowMimeTypeException extends Exception
{
	public function __construct()
    {
        parent::__construct("Unknonw mime type.");
    }
}