<?php

namespace Dontdrinkandroot\BridgeBundle\Config\Routes;

use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginAction;
use Dontdrinkandroot\BridgeBundle\Model\Container\RouteName;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add(RouteName::SECURITY_LOGIN, '/login')
        ->controller(LoginAction::class)
        ->methods(['GET', 'POST']);
};
