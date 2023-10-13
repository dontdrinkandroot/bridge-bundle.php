<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Config;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('@DdrBridgeBundle/config/routes/health.php');
    $routes->import('.', 'api_platform')->prefix('/api');
};