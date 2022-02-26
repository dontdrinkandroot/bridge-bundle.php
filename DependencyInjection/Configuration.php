<?php

namespace Dontdrinkandroot\BridgeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
