<?php

namespace btba\ChatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('btba_chat');

        $rootNode
            ->children()
                ->scalarNode('update_interval')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('message_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('author_class')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;

        return $treeBuilder;

    }
}