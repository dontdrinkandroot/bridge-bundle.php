<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Controller\HealthAction;
use Dontdrinkandroot\BridgeBundle\Model\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add(Route::HEALTH, '/_health')
        ->controller(HealthAction::class)
        ->methods(['GET']);
};
