<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Menu\NavbarBuilder;
use Dontdrinkandroot\BridgeBundle\Model\Container\ParamName;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(NavbarBuilder::class)
        ->args([
            service('knp_menu.factory'),
            service('event_dispatcher'),
            param(ParamName::USER_ENABLED),
            service(Security::class)->ignoreOnInvalid()
        ])
        ->tag('knp_menu.menu_builder', ['method' => 'createLeft', 'alias' => 'ddr_navbar_left'])
        ->tag('knp_menu.menu_builder', ['method' => 'createRight', 'alias' => 'ddr_navbar_right']);
};
