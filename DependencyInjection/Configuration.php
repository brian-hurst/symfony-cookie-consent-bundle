<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace FatalNetwork\CookieConsentBundle\DependencyInjection;

use FatalNetwork\CookieConsentBundle\Enum\CategoryEnum;
use FatalNetwork\CookieConsentBundle\Enum\PositionEnum;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('fn_cookie_consent');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = /* @scrutinizer ignore-deprecated */ $treeBuilder->root('fn_cookie_consent');
        }

        $rootNode
            ->children()
                ->variableNode('categories')
                    ->defaultValue([CategoryEnum::CATEGORY_ANALYTICS, CategoryEnum::CATEGORY_MARKETING, CategoryEnum::CATEGORY_PREFERENCES])
                ->end()
                ->enumNode('position')
                    ->defaultValue(PositionEnum::POSITION_TOP)
                    ->values(PositionEnum::getAvailablePositions())
                ->end()
                ->booleanNode('use_logger')
                    ->defaultTrue()
                ->end()
                ->booleanNode('http_only')
                    ->defaultTrue()
                ->end()
                ->scalarNode('form_action')
                    ->defaultNull()
                ->end()
                ->booleanNode('csrf_protection')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
