<?php

namespace Armetiz\MediaBundle\Provider;

class YoutubeProvider extends AbstractServiceProvider
{
    public function getMimeType() {
        return "x-armetiz/youtube";
    }
    
    public function getMediaPattern() {
        return "/youtube\/([A-Za-z0-9_\-]+)/";
    }
    
    public function getDefaultTemplate() {
        return "ArmetizMediaBundle:Youtube:iframe.html.twig";
    }
}