<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaAdvancedInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;
use Armetiz\MediaBundle\Entity\FormatInterface;

use Armetiz\MediaBundle\Exceptions\MustPreparedException;
use Armetiz\MediaBundle\Exceptions\UnknowMimeTypeException;
use Armetiz\MediaBundle\Exceptions\NotSupportedMediaException;

use Armetiz\MediaBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

use Symfony\Component\HttpFoundation\File\File;

use Gaufrette\File as GaufretteFile;

class FileProvider extends AbstractProvider
{
    public function validate (MediaInterface $media) {}
    
    public function canHandleMedia (MediaInterface $media)
    {
        $extension = ExtensionGuesser::guess($media->getContentType());
        
        return ((null !== $extension) || $media->getMedia() && ($media->getMedia() instanceof File || is_file($media->getMedia())));
    }
    
    public function saveMedia (MediaInterface $media)
    {
        if (!$media->getMedia() instanceof File) {
            if (!$this->canHandleMedia($media)) {
                throw new NotSupportedMediaException();
            }
            else {
                throw new MustPreparedException();
            }
        }
        
        $this->validate($media);
                       
        $path = $this->getPath($media);
        
        $gaufretteFile = new GaufretteFile($path, $this->getFilesystem());
        $gaufretteFile->setContent(file_get_contents($media->getMedia()->getRealPath()));
    }
    
    public function deleteMedia (MediaInterface $media)
    {
        if (!$this->canHandleMedia($media)) {
            throw new NotSupportedMediaException();
        }
        
        $path = $this->getPath($media);
        
        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        parent::prepareMedia($media);
        
        if (!$media->getMedia() instanceof File && is_file($media->getMedia())) {
            $media->setMedia(new File($media->getMedia()));
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
        
        $media->setContentType($media->getMedia()->getMimeType());
    }
    
    public function getRaw (MediaInterface $media)
    {
        return $this->getFilesystem()->read($this->getPath($media));
    }
    
    public function getUri (MediaInterface $media)
    {
        $path = $this->getPath ($media);
        
        return $this->getContentDeliveryNetwork()->getPath($path);
    }
    
    public function getPath (MediaInterface $media)
    {
        $path = $media->getMediaIdentifier();
        
        return sprintf ("%s/%s", $this->getNamespace(), $path);
    }
}