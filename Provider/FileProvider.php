<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaTimestampableInterface;
use Armetiz\MediaBundle\Entity\MediaAdvancedInterface;
use Armetiz\MediaBundle\Entity\MediaInterface;

use Armetiz\MediaBundle\Exceptions\MustPreparedException;
use Armetiz\MediaBundle\Exceptions\UnknowMimeTypeException;
use Armetiz\MediaBundle\Exceptions\NotSupportedMediaException;

use Armetiz\MediaBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

use Symfony\Component\HttpFoundation\File\File;

use Gaufrette\File as GaufretteFile;

class FileProvider extends AbstractProvider
{
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
        
        $path = $this->getPath($media, "original");
        
        $gaufretteFile = new GaufretteFile($path, $this->getFilesystem());
        $gaufretteFile->setContent(file_get_contents($media->getMedia()->getRealPath()));
        
        return $this;
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
        
        return $this;
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
        
        return $this;
    }
    
    public function getUri (MediaInterface $media, $format)
    {
        $path = $this->getPath ($media, $format);
        
        return $this->getContentDeliveryNetwork()->getPath($path);
    }
    
    protected function getPath (MediaInterface $media, $format)
    {
        $path = $this->getNamespace();
        
        if ($media instanceof MediaTimestampableInterface) {
            $dateCreation = $media->getDateCreation();
            $path = $path . "/" . $dateCreation->format("Y-m");
        }
        
        $pathInfo = pathinfo($media->getMediaIdentifier());
        
        return $path . "/" . $pathInfo["filename"] . "_" . $format . "." . $pathInfo["extension"];
    }
    
    public function getOriginalFile(MediaInterface $media)
    {
        return $this->getFilesystem()->get($this->getPath($media, "original"), true);
    }
    
    public function getDefaultFormats()
    {
        return array("original" => null);
    }
}