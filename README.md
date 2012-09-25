LeezyMediaBundle
=====================

A Media Manager Bundle

## Installation

Installation is a quick 3 step process:

1. Download LeezyMediaBundle using composer
2. Enable the Bundle
3. Configure your application's config.yml

### Step 1: Download LeezyMediaBundle using composer

Add LeezyMediaBundle in your composer.json:

```js
{
    "require": {
        "leezy/media-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update leezy/media-bundle
```

Composer will install the bundle to your project's `vendor/leezy` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Leezy\MediaBundle\LeezyMediaBundle(),
    );
}
```
### Step 3: Configure your application's config.yml

Finally, add the following to your config.yml

``` yaml
# app/config/config.yml
leezy_media:
    storages:
        media:
            service: gaufrette.medias
    cdns: 
        local:
            base_url: %website_url%/medias
    providers:
        subtitles:
            filesystem: media
            cdn: local
            template: LeezyMediaBundle:Subtitle:default.html.twig
        thumbnail:
            filesystem: media
            cdn: local
    contexts:
        subtitles:
            managed: Acme\SubtitleBundle\Entity\Subtitle
            provider: subtitles
        thumbnail:
            managed: Acme\PosterBundle\Entity\Thumbnail
            provider: thumbnail
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
-
-

**Note:**