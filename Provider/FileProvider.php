<?php

namespace Leezy\MediaBundle\Provider;

use Leezy\MediaBundle\Models\MediaAdvancedInterface;
use Leezy\MediaBundle\Models\MediaInterface;
use Leezy\MediaBundle\CDN\CDNInterface;

use Leezy\MediaBundle\Exceptions\IdentifierEmptyException;
use Leezy\MediaBundle\Exceptions\MustPreparedException;
use Leezy\MediaBundle\Exceptions\NotFileException;
use Leezy\MediaBundle\Exceptions\UnknowMimeTypeException;

use Leezy\SystemBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

use Symfony\Component\HttpFoundation\File\File;

class FileProvider extends AbstractProvider
{  
    public function saveMedia (MediaInterface $media)
    {
        if (null == $media->getMedia()){
            return null;
        }
        
        if (!$media->getMedia() instanceof File) {
            throw new MustPreparedException();
        }
                       
        $path = $this->getPathGenerator()->generatePath ($media);
        
        $gaufretteFile  = $this->getFilesystem()->get($path, true);
        $gaufretteFile->setContent(file_get_contents($media->getMedia()->getRealPath()));
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        //TODO: Check is media has been prepared.
        
        $path = $this->getPathGenerator()->generatePath ($media);
        
        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }
    }
    
    public function prepareMedia (MediaInterface $media, $format = null)
    {
        
        $content = $media->getMedia();
        
        if (empty($content)) {
            return;
        }
        
        if (!$content instanceof File) {
            if (!is_file($content)) {
                throw new NotFileException();
            }

            $media->setMedia(new File($content));
        }
        
        if (!$media->getMediaIdentifier()) {
            if ($media instanceof MediaAdvancedInterface && $media->getMediaIdentifierBase()) {
                $identifierBase = $media->getMediaIdentifierBase();
            }
            else {
                $identifierBase = uniqid();
            }
            
            $extension = ExtensionGuesser::guess($media->getMedia()->getMimeType());

            if (!$extension) {
                throw new UnknowMimeTypeException();
            }

            $media->setMediaIdentifier($identifierBase . "." . $extension);
        }
        
        if ($media instanceof MediaAdvancedInterface && !$media->getMediaIdentifierBase()) {
            $extension = strrchr($media->getMediaIdentifier(), '.');  
            if($extension) {  
                $identifierBase = substr($media->getMediaIdentifier(), 0, -strlen($extension));  
            }  
            $media->setMediaIdentifierBase($identifierBase);
        }
        
        if ( $media instanceof MediaAdvancedInterface) {
            $media->setContentType($media->getMedia()->getMimeType());
        }
    }
    
    public function getRaw (MediaInterface $media)
    {
        $path = $this->getPathGenerator()->generatePath ($media);
        $raw = null;
        try
        {
            $raw = $this->getFilesystem()->read($path);
        }
        catch (\Exception $e)
        {
            
        }
        
        return $raw;
    }
}