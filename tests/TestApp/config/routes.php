<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Config;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('.', 'api_platform')->prefix('/api');
    $routes->import('@DdrBridgeBundle/config/routes/health.php');
};
