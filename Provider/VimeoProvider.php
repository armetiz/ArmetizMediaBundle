<?php

namespace Armetiz\MediaBundle\Provider;

class VimeoProvider extends AbstractServiceProvider
{
    public function getMimeType() {
        return "x-armetiz/vimeo";
    }
    
    public function getMediaPattern() {
        return "/vimeo\/([A-Za-z0-9_\-]+)/";
    }
    
    public function getDefaultTemplate() {
        return "ArmetizMediaBundle:Vimeo:iframe.html.twig";
    }
}