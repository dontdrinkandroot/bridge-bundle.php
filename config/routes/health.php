<?php

namespace Dontdrinkandroot\BridgeBundle\Config\Routes;

use Dontdrinkandroot\BridgeBundle\Controller\HealthAction;
use Dontdrinkandroot\BridgeBundle\Model\Container\RouteName;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add(RouteName::HEALTH, '/_health')
        ->controller(HealthAction::class)
        ->methods(['GET']);
};
