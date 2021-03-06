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
        $loader->load('providers.xml');
        
        $contextClass = $container->getParameter("armetiz.media.context.class");
        $cdnClass = $container->getParameter("armetiz.media.cdn.class");

        $manager = $container->getDefinition("armetiz.media.manager");
        $filesystems = array();
        $cdns = array();
        
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
        
        $managedClassesMapped = array();
        
        foreach ($config["contexts"] as $name => $context) {
            $managedClasses = $context["managed"];
            $contextedProviders = array();
            $contextedFormats = array();
            
            foreach ($managedClasses as $managedClass) {
                if(in_array($managedClass, $managedClassesMapped)) {
                    throw new \RuntimeException("Class already mapped: " . $managedClass);
                }
                
                $managedClassesMapped[] = $managedClass;
            }
            
            foreach ($context["providers"] as $providerName => $providerInformations) {
                $contextedFormat = array();
                
                //loop on context format
                foreach($context["formats"] as $formatName => $formatOptions) {
                    $formatTransformerRef = new Reference("armetiz.media.transformer.null");
                    
                    if(array_key_exists($formatName, $providerInformations["formats"])) {
                        if (array_key_exists("options", $providerInformations["formats"][$formatName])) {
                            $formatOptions = array_merge($formatOptions, $providerInformations["formats"][$formatName]["options"]);
                        }
                        
                        if (array_key_exists("transformer", $providerInformations["formats"][$formatName])) {
                            $formatTransformerRef = new Reference($providerInformations["formats"][$formatName]["transformer"]);
                        }
                    }
                    
                    $contextedFormatArgs = array (
                        $formatName, 
                        $formatTransformerRef,
                        $formatOptions
                    );
                    
                    $contextedFormat[] = new Definition("Armetiz\MediaBundle\Format", $contextedFormatArgs);
                }
                
                $contextedProviders[$providerName] = new Reference($providerName);
                $contextedFormats[$providerName] = $contextedFormat;
            }
            
            $context = new Definition($contextClass);
            $context->addMethodCall("setName", array($name));
            $context->addMethodCall("setProviders", array($contextedProviders, $contextedFormats));
            $context->addMethodCall("setManagedClasses", array($managedClasses));

            //TODO:: setProviders to context
            $manager->addMethodCall("addContext", array($context));
        }
    }
}
