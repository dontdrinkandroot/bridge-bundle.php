<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Controller\Security\ResetPasswordAction;
use Dontdrinkandroot\BridgeBundle\Service\Security\ResetPasswordService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(ResetPasswordAction::class, ResetPasswordAction::class)
        ->autoconfigure()
        ->autowire();

    $services->set(ResetPasswordService::class, ResetPasswordService::class)
        ->autoconfigure()
        ->autowire();
};
