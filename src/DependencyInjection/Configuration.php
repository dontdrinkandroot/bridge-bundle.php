<?php

namespace Dontdrinkandroot\BridgeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use TKS\ConnectOnlineBridgeBundle\User\Form\Type\ConnectOnlineUserType;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_bridge');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode->children()
            ->arrayNode('user')
                ->canBeDisabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('class')->defaultValue('App\Entity\User')->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
