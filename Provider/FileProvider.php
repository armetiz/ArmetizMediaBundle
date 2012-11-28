<?php

namespace Armetiz\MediaBundle\Provider;

use Armetiz\MediaBundle\Entity\MediaInterface;

use Armetiz\MediaBundle\Format;
use Armetiz\MediaBundle\Exceptions\MustPreparedException;
use Armetiz\MediaBundle\Exceptions\NotSupportedMediaException;
use Armetiz\MediaBundle\Exceptions\NotSupportedFormatException;
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
    
    public function getProviderOptions()
    {
        return array (
            "filesystem" => $this->getFilesystem(),
        );
    }
    
    public function generateFormats(MediaInterface $media)
    {
        foreach($this->getFormats() as $format) {
            $format->getTransformer()->create($media, $format);
        }
        
        return $this;
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
        
        $path = $this->getPath($media);
        
        $gaufretteFile = new GaufretteFile($path, $this->getFilesystem());
        $gaufretteFile->setContent(file_get_contents($media->getMedia()->getRealPath()));
        
        $this->generateFormats($media);
        
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
        
        //TODO: Delete also formats
        
        return $this;
    }
    
    public function prepareMedia (MediaInterface $media)
    {
        parent::prepareMedia($media);
        
        if (!$media->getMedia() instanceof File && is_file($media->getMedia())) {
            $media->setMedia(new File($media->getMedia()));
        }
        
        if (!$media->getMediaIdentifier()) {
            $media->setMediaIdentifier(uniqid());
        }
        
        $media->setContentType($media->getMedia()->getMimeType());
        
        return $this;
    }
    
    public function getUri (MediaInterface $media, Format $format = null)
    {
        $path = $this->getPath($media, $format);
        
        return $this->getContentDeliveryNetwork()->getPath($path);
    }
    
    protected function getPath (MediaInterface $media, Format $format = null)
    {
        if (null === $format) {
            return $this->getPathGenerator()->getPath($media, null);
        }
        elseif($this->hasFormat($format)) {
            return $this->getFormat($format)->getTransformer()->getPath($media, $format);
        }
        else {
            throw new NotSupportedFormatException($this, $this->getFormats(), $format);
        }
    }
}