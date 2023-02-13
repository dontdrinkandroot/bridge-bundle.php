<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Menu\NavbarBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(NavbarBuilder::class)
        ->args([
            service('knp_menu.factory'),
            service('event_dispatcher')
        ])
        ->tag('knp_menu.menu_builder', ['method' => 'createLeft', 'alias' => 'ddr_navbar_left'])
        ->tag('knp_menu.menu_builder', ['method' => 'createRight', 'alias' => 'ddr_navbar_right']);
};
