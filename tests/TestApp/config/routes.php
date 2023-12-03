<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Config;

use Dontdrinkandroot\BridgeBundle\Tests\TestApp\Controller\BaseTemplateAction;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('@DdrBridgeBundle/config/routes/health.php');
    $routes->import('.', 'api_platform')->prefix('/api');
    $routes
        ->add('ddr_bridge.test.base_template', '/base_template')
        ->controller(BaseTemplateAction::class);
};
