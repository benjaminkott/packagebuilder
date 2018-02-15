<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\MenuFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MenuBuilder
 */
class MenuBuilder
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var MenuFactory
     */
    private $factory;

    /**
     * @var MatcherInterface
     */
    private $matcher;

    /**
     * @param ContainerInterface $container
     * @param FactoryInterface $factory
     * @param MatcherInterface $matcher
     */
    public function __construct(ContainerInterface $container, FactoryInterface $factory, MatcherInterface $matcher)
    {
        $this->container = $container;
        $this->factory = $factory;
        $this->matcher = $matcher;
    }

    /**
     * @param array $options
     */
    public function main(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'default_index']);
        $menu->addChild('New Sitepackage', ['route' => 'default_new']);
        return $menu;
    }
}
