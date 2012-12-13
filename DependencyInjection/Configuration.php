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
                ->arrayNode("filesystems")
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode("cdns")
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
                ->arrayNode("contexts")
                    ->cannotBeEmpty()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('managed')
                                ->cannotBeEmpty()
                                ->isRequired()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('formats')
                                ->cannotBeEmpty()
                                ->isRequired()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('width')->defaultValue(false)->end()
                                        ->scalarNode('height')->defaultValue(false)->end()
                                        ->scalarNode('quality')->defaultValue(80)->end()
                                        ->scalarNode('format')->defaultValue('jpg')->end()
                                        ->scalarNode('constraint')->defaultValue(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('providers')
                                ->cannotBeEmpty()
                                ->isRequired()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->arrayNode('formats')
                                            ->cannotBeEmpty()
                                            ->useAttributeAsKey('name')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('transformer')
                                                        ->cannotBeEmpty()
                                                        ->isRequired()
                                                    ->end()
                                                    ->arrayNode('options')
                                                        ->useAttributeAsKey('name')
                                                        ->cannotBeEmpty()
                                                        ->prototype('scalar')->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
