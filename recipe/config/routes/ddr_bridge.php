<?php

namespace App\Config\Routes;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('@DdrBridgeBundle/config/routes/health.php');
//    $routes->import('@DdrBridgeBundle/config/routes/login_logout.php');
//    $routes->import('@DdrBridgeBundle/config/routes/reset_password.php');
};
