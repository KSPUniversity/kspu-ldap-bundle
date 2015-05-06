<?php

namespace Kspu\LdapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kspu_ldap');

        $rootNode
                ->children()
                    ->scalarNode('user_store')->isRequired()->cannotBeEmpty()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
