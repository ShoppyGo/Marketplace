<?php

namespace ShoppyGo\MarketplaceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder $builder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $rootNode = $builder->root('shoppy_go_marketplace');
        $rootNode->children()
//            ->arrayNode('hook_services')
//                ->arrayPrototype()
//                    ->children()
//                        ->scalarNode('hook')->end()
//                        ->scalarNode('service')->end()
//                    ->end()
//                ->end()
//            ->end()
        ->end();

        return $builder;
    }
}
