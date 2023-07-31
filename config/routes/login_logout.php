<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginAction;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LogoutAction;
use Dontdrinkandroot\BridgeBundle\Model\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add(Route::SECURITY_LOGIN, '/login')
        ->controller(LoginAction::class)
        ->methods(['GET', 'POST']);

    $routes->add(Route::SECURITY_LOGOUT, '/logout')
        ->controller(LogoutAction::class)
        ->methods(['GET']);
};
