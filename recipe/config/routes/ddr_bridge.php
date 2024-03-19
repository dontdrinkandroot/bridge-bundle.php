<?php

namespace App\Config\Routes;

use Dontdrinkandroot\BridgeBundle\Routing\DdrBridgeLoader;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('.', DdrBridgeLoader::TYPE);
};
