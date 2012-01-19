<?php

namespace Leezy\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LeezyMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $contextClass = $container->getParameter("leezy.media.context.class");
        $cdnClass = $container->getParameter("leezy.media.cdn.class");
        $pathGeneratorClass = $container->getParameter("leezy.media.generator.path.class");
        $mediaFileProviderClass = $container->getParameter("leezy.media.provider.file.class");
        $mediaImageProviderClass = $container->getParameter("leezy.media.provider.image.class");

        $manager = $container->getDefinition("leezy.media.manager");
        $storages = array();
        $cdns = array();
        $providers = array();
        $contexts = array();
        
        foreach ($config["storages"] as $name => $storage) {
            $folder = $storage["folder"];
            $clazz = $storage["adapter"];
            
            $adapter = new Definition ($clazz, array ($folder));
            $storages[$name] = new Definition ("Gaufrette\Filesystem", array ($adapter));
        }
        
        foreach ($config["cdns"] as $name => $cdn) {
            $baseUrl = $cdn["base_url"];
            
            $cdns[$name] = new Definition ($cdnClass, array ($baseUrl));
        }
        
        foreach ($config["providers"] as $name => $provider) {
            $filesystem = $storages[$provider["filesystem"]];
            $cdn = $cdns[$provider["cdn"]];
            $namespace = $name;
            $template = null;
            
            if (array_key_exists("template", $provider))
                $template = $provider["template"];
            
            if (array_key_exists("namespace", $provider))
                $namespace = $provider["namespace"];
            
            switch ($provider["type"]) {
                case "file" :
                    $mediaProviderClass = $mediaFileProviderClass;
                    break;
                case "image" :
                    $mediaProviderClass = $mediaImageProviderClass;
                    break;
            }
            
            $providers[$name] = new Definition ($mediaProviderClass, array ($filesystem, $cdn, $pathGeneratorClass, $namespace, $template));
        }
        
        foreach ($config["contexts"] as $name => $context) {
            $provider = $providers[$context["provider"]];
            $managed = $context["managed"];
            $formats = $context["formats"];
            
            $contexts[$name] = new Definition ($contextClass, array ($name, $provider, array($managed), $formats));
            
            $manager->addMethodCall("addContext", array($contexts[$name]));
        }
    }
}
