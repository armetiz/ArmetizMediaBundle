<?php

namespace Armetiz\MediaBundle\Provider;

class GMapProvider extends AbstractServiceProvider
{
    public function getMimeType() {
        return "x-armetiz/gmap";
    }
    
    public function getMediaPattern() {
        return "/gmap\/(.*)/";
    }
    
    public function getDefaultTemplate() {
        return "ArmetizMediaBundle:GMap:iframe.html.twig";
    }
}