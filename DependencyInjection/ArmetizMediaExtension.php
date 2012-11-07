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
        $mediaFileProviderClass = $container->getParameter("armetiz.media.provider.file.class");

        $manager = $container->getDefinition("armetiz.media.manager");
        $filesystems = array();
        $cdns = array();
        $providers = array();
        
        $filesystems["default"] = new Definition ("Gaufrette\Filesystem", array ("armetiz.media.storage.default"));
        
        if ($config["filesystems"]) {
            foreach ($config["filesystems"] as $name => $filesystem) {
                $adapter = new Reference($filesystem['id']);

                $filesystems[$name] = new Definition ("Gaufrette\Filesystem", array ($adapter));
            }
        }
        
        foreach ($config["cdns"] as $name => $cdn) {
            $baseUrl = $cdn["base_url"];
            
            $cdns[$name] = new Definition ($cdnClass, array ($baseUrl));
        }
        
        foreach ($config["providers"] as $name => $provider) {
            $filesystem = $filesystems[$provider["filesystem"]];
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
            }
            
            $provider = new Definition($mediaProviderClass);
            $provider->addMethodCall("setFilesystem", array($filesystem));
            $provider->addMethodCall("setContentDeliveryNetwork", array($cdn));
            $provider->addMethodCall("setTemplate", array($template));
            $provider->addMethodCall("setNamespace", array($namespace));
            
            $container->setDefinition("armetiz.media.providers." . $name, $provider);
            
            $providers[$name] = $provider;
        }
        
        foreach ($config["contexts"] as $name => $context) {
            $managed = $context["managed"];
            $formats = $context["formats"];
            $contextedProviders = array();
            
            foreach ($context["providers"] as $providerName) {
                $contextedProvider = null;
                
                if (array_key_exists($providerName, $providers)) {
                    $contextedProvider = $providers[$providerName];
                }
                
                if ($container->hasDefinition($providerName)) {
                    $contextedProvider = $container->getDefinition($providerName);
                }
                
                if (null === $contextedProvider) {
                    throw new \RuntimeException("Access to an undefined provider: " . $providerName);
                }
                
                $contextedProviders[] = $contextedProvider;
            }
            
            $context = new Definition($contextClass, array ($name, $provider, array($managed), $formats));
            $context->addMethodCall("setName", array($name));
            $context->addMethodCall("setProviders", array($contextedProviders));
            $context->addMethodCall("setManagedClasses", array(array($managed)));
            $context->addMethodCall("setFormats", array($formats));

            //TODO:: setProviders to context
            
            $manager->addMethodCall("addContext", array($context));
        }
    }
}
