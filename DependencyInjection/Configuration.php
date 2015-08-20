<?php

namespace Jfdl\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jfdl_form');

        $rootNode
            ->children()
                ->arrayNode('form_types')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('select2_ajax_entity')->defaultFalse()->end()
                    ->end()

                ->end()
            ->end()
        ;

        return $treeBuilder;
    }




}
