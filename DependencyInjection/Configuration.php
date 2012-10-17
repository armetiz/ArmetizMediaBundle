<?php

namespace Armetiz\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('armetiz_media');

        $rootNode
            ->children()
                ->arrayNode("storages")
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('service')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode("cdns")
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('base_url')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode("providers")
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('filesystem')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                            ->scalarNode('cdn')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                            ->scalarNode('namespace')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('template')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('type')
                                ->cannotBeEmpty()
                                ->defaultValue("file")
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode("contexts")
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('managed')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                            ->scalarNode('default_media')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('provider')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                            ->arrayNode("formats")
                                ->cannotBeEmpty()
                                ->useAttributeAsKey("name")
                                ->prototype('array')
                                    ->useAttributeAsKey("name")
                                    ->prototype("scalar")->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
