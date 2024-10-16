<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;

/**
 * MenuBuilder.
 */
class MenuBuilder
{
    public function __construct(private readonly FactoryInterface $factory)
    {
    }

    public function main(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'default_index']);
        $menu->addChild('New Sitepackage', ['route' => 'default_new']);
        $menu->addChild('API', ['route' => 'api_docs']);

        return $menu;
    }
}
