ArmetizMediaBundle
=====================
[![project status](http://stillmaintained.com/armetiz/ArmetizMediaBundle.png)](http://stillmaintained.com/armetiz/ArmetizMediaBundle)
[![Build Status](https://secure.travis-ci.org/armetiz/ArmetizMediaBundle.png)](http://travis-ci.org/armetiz/ArmetizMediaBundle)

A Media Manager Bundle.

## Todo
Do something to provide an automatic CDN http://domain.tld/medias/
Add "base_folder: medias/"
Use it to Gaufrette Adapter & CDN

Move "namespace" from Provider to Context. Tell the MediaManager to use Context params on selected Provider

L'utilisation d'un "transformer.null" permet d'utiliser les traitements par défaut du Provider sur different format

Enregistrer le context & le provider au sein du Media directement : eviter la detection automatique du context & du provider.
Cela permet aussi d'utiliser un Media dans plusieurs contexts sans devoir définir de ClassEntity.


Rendu de vue

options = {
    transformer: "thumbnail",
    transformer_options: {  //Optional, use default transformer option and merge with it
        engine: gd
    }
};

mediaRender (monMedia, "format_name", options);

//Numerosus
Pour choisir le type de rendu, utiliser les options de rendu avec un Helper Twig.
Cela va eviter de compliquer le dévleoppement du MediaManager avec la configuration des formats par Provider.. Ce qui allourdit la configuration énormement

## Installation

Installation is a quick 3 step process:

1. Download ArmetizMediaBundle using composer
2. Enable the Bundle
3. Configure your application's config.yml

### Step 1: Download ArmetizMediaBundle using composer

Add ArmetizMediaBundle in your composer.json:

```js
{
    "require": {
        "armetiz/media-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update armetiz/media-bundle
```

Composer will install the bundle to your project's `vendor/armetiz` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Armetiz\MediaBundle\ArmetizMediaBundle(),
    );
}
```
### Step 3: Configure your application's config.yml

Finally, add the following to your config.yml

``` yaml
# app/config/config.yml
armetiz_media:
    filesystems:
        fake:
            id: fake.service
    cdns: 
        local:
            base_url: %website_url%/medias
    contexts:
        default:
            providers: 
                armetiz.media.provider.youtube: ~
                armetiz.media.provider.image:
                    formats:
                        annexe_display:
                            transformer: armetiz.media.transformer.image_to_thumbnail
            formats:
                thumbnail: {width: 512}
```

## Configuration
This bundle can be configured, and this is the list of what you can do :
- A default storage exists. The path is "web/medias".
- Context can take provider defined anywhere (see above: app.media.provider_foo)
- Context can take many provider. Each one handle a specific media. Is many provider can handle a media, the defined first will be choosen

**Note:**

## Usage
#Twig
Some helpers have been created to Twig. In fact, you can : render the media using templates, get the raw media, and uri or a simple path. 

``` yaml
{{ media(thumbnail) }}
{{ media(thumbnail, {template: foo}) }}
{{ mediaPath(thumbnail) }}
{{ mediaUri(thumbnail) }}
{{ mediaRaw(thumbnail) }}
```

## Context
A context is a simple mapping between an Media & Providers.

## Provider
A provider is the specific manager for a specific Media. It can be a FileProvider, YoutubeProvider, FlickrProvider, ImageProvider...
Existing type :
* file (which is the default)
* image
* youtube
* dailymotion
* vimeo
* google maps

Note: If you define more than one provider for a single context. Put the more restrictive on top !