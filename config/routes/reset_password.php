<?php

namespace Dontdrinkandroot\BridgeBundle\Config\Routes;

use Dontdrinkandroot\BridgeBundle\Controller\Security\ResetPasswordAction;
use Dontdrinkandroot\BridgeBundle\Model\Container\RouteName;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->add(RouteName::SECURITY_RESET_PASSWORD, '/reset-password')
        ->controller(ResetPasswordAction::class)
        ->methods(['GET', 'POST']);
};
