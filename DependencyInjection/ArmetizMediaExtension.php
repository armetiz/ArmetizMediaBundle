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
        
        $contextClass = $container->getParameter("armetiz.media.context.class");
        $cdnClass = $container->getParameter("armetiz.media.cdn.class");

        $manager = $container->getDefinition("armetiz.media.manager");
        $filesystems = array();
        $cdns = array();
        $providers = array();
        
        $filesystems["default"] = new Reference("armetiz.media.filesystem.default");
        
        if ($config["filesystems"]) {
            foreach ($config["filesystems"] as $name => $filesystem) {
                $adapter = new Reference($filesystem['id']);

                $filesystems[$name] = new Definition ("Gaufrette\Filesystem", array ($adapter));
            }
        }
        
        $cdns["default"] = new Reference("armetiz.media.cdn.default");
        
        foreach ($config["cdns"] as $name => $cdn) {
            $baseUrl = $cdn["base_url"];
            
            $cdns[$name] = new Definition ($cdnClass, array ($baseUrl));
        }
        
        foreach ($config["providers"] as $name => $provider) {
            if (!array_key_exists($provider["filesystem"], $filesystems)) {
                throw new \RuntimeException("Access to an undefined filesystem: " . $provider["filesystem"]);
            }
            
            $filesystem = $filesystems[$provider["filesystem"]];
            $cdn = $cdns[$provider["cdn"]];
            $namespace = $name;
            $templates = array();
            
            if (array_key_exists("templates", $provider))
                $templates = $provider["templates"];
            
            if (array_key_exists("namespace", $provider))
                $namespace = $provider["namespace"];
            
            switch ($provider["type"]) {
                case "file" :
                    $mediaProviderClass = "Armetiz\MediaBundle\Provider\FileProvider";
                    break;
                case "youtube" :
                    $mediaProviderClass = "Armetiz\MediaBundle\Provider\YoutubeProvider";
                    break;
                case "dailymotion" :
                    $mediaProviderClass = "Armetiz\MediaBundle\Provider\DailymotionProvider";
                    break;
                case "vimeo" :
                    $mediaProviderClass = "Armetiz\MediaBundle\Provider\VimeoProvider";
                    break;
                case "gmap" :
                    $mediaProviderClass = "Armetiz\MediaBundle\Provider\GMapProvider";
                    break;
                case "image" :
                    $mediaProviderClass = "Armetiz\MediaBundle\Provider\ImageProvider";
                    break;
                default: 
                    throw new \RuntimeException("ArmetizMediaBundle, unknown type: '" . $provider["type"] . "' for provider: '" . $name . "'");
            }
            
            $provider = new Definition($mediaProviderClass);
            $provider->addMethodCall("setFilesystem", array($filesystem));
            $provider->addMethodCall("setContentDeliveryNetwork", array($cdn));
            $provider->addMethodCall("setTemplates", array($templates));
            $provider->addMethodCall("setNamespace", array($namespace));
            
            $container->setDefinition("armetiz.media.providers." . $name, $provider);
            
            $providers[$name] = $provider;
        }
        
        $managedClassesMapped = array();
        
        foreach ($config["contexts"] as $name => $context) {
            $managedClasses = $context["managed"];
            $contextedProviders = array();
            
            foreach ($managedClasses as $managedClass) {
                if(in_array($managedClass, $managedClassesMapped)) {
                    throw new \RuntimeException("Class already mapped: " . $managedClass);
                }
                
                $managedClassesMapped[] = $managedClass;
            }
            
            foreach ($context["providers"] as $providerName) {
                $contextedProvider = null;
                
                if (array_key_exists($providerName, $providers)) {
                    $contextedProvider = $providers[$providerName];
                }
                
                if (null === $contextedProvider) {
                    $contextedProvider = new Reference($providerName);
                }
                
                $contextedProviders[] = $contextedProvider;
            }
            
            $context = new Definition($contextClass);
            $context->addMethodCall("setName", array($name));
            $context->addMethodCall("setProviders", array($contextedProviders));
            $context->addMethodCall("setManagedClasses", array($managedClasses));

            //TODO:: setProviders to context
            
            $manager->addMethodCall("addContext", array($context));
        }
    }
}
