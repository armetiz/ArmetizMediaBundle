<?php

namespace Armetiz\MediaBundle\Provider;

class DailymotionProvider extends AbstractServiceProvider
{
    public function getMimeType() {
        return "x-armetiz/dailymotion";
    }
    
    public function getMediaPattern() {
        return "/dailymotion\/([A-Za-z0-9_\-]+)/";
    }
    
    public function getDefaultTemplate() {
        return "ArmetizMediaBundle:Dailymotion:iframe.html.twig";
    }
}