<?php

namespace Dontdrinkandroot\BridgeBundle\DependencyInjection;

use Override;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    #[Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_bridge');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode->children()
            ->arrayNode('user')
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('class')->defaultValue('App\Entity\User')->end()
                    ->booleanNode('reset_password')->defaultFalse()->end()
                ->end()
            ->end()
            ->arrayNode('mail')
                ->children()
                    ->arrayNode('address')
                        ->isRequired()
                        ->children()
                            ->scalarNode('from')->isRequired()->end()
                            ->scalarNode('reply_to')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
