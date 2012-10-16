<?php

namespace Armetiz\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ArmetizMediaExtension extends Extension
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
        
        $contextClass = $container->getParameter("Armetiz.media.context.class");
        $cdnClass = $container->getParameter("Armetiz.media.cdn.class");
        $mediaFileProviderClass = $container->getParameter("Armetiz.media.provider.file.class");
        $mediaImageProviderClass = $container->getParameter("Armetiz.media.provider.image.class");

        $manager = $container->getDefinition("Armetiz.media.manager");
        $storages = array();
        $cdns = array();
        $providers = array();
        $contexts = array();
        
        foreach ($config["storages"] as $name => $storage) {
            $adapter = new Reference($storage['service']);
            
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
            
            $providers[$name] = new Definition ($mediaProviderClass, array ($filesystem, $cdn, $namespace, $template));
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
