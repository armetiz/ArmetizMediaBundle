ArmetizMediaBundle
=====================

[![Build Status](https://secure.travis-ci.org/armetiz/ArmetizMediaBundle.png)](http://travis-ci.org/armetiz/ArmetizMediaBundle)

A Media Manager Bundle.

## Todo
For FileProvider. Do not use the provider name to chose the folder. Maybe use the storage ?
Use a PathGenerator. Inject the Provider, the Context? & the Media to the PathGenerator.

Think about how to remove MediaManager::prepareMedia. It's something that can be easily forgotten.

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
    providers:  //use this section to configure pre-defined provider
        subtitles:
            type: file
            cdn: local
            template: ArmetizMediaBundle:Subtitle:default.html.twig
        thumbnail:
            filesystem: fake
            cdn: local
    contexts:
        subtitles:
            managed: Acme\FileBundle\Entity\File
            templates:
                default: AcmeSiteBundle:Media:subtitles.html.twig
                foo: AcmeSiteBundle:Media:subtitles_foo.html.twig
            providers: 
                - app.media.provider_foo
                - app.media.provider_bar
        subtitles:
            managed: Acme\SubtitleBundle\Entity\Subtitle
            providers: 
                - subtitles
        thumbnail:
            managed: Acme\PosterBundle\Entity\Thumbnail
            providers: 
                - thumbnail
            default_media: default.jpg
            formats:
                128x72:
                    folder: thumbnail_128_72
                    width: 128
                    height: 72
                256x144:
                    folder: thumbnail_256_144
                    width: 256
                    height: 144
```

## Configuration
This bundle can be configured, and this is the list of what you can do :
- A default storage exists. The path is "web/medias".
- Context can take provider defined anywhere (see above: app.media.provider_foo)
- Context can take many provider. Each one handle a specific media. Is many provider can handle a media, the defined first will be choosen

**Note:**

## Context
A context is a simple mapping between an Media & Providers.

## Provider
A provider is the specific manager for a specific Media. It can be a FileProvider, YoutubeProvider, FlickrProvider, ImageProvider...