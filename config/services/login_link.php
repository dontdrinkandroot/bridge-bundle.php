<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Command\User\EditCommand;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginLink\CheckAction;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginAction;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginLink\RequestAction;
use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(RequestAction::class)
        ->autoconfigure()
        ->autowire()
        ->tag('controller.service_arguments');

    $services->set(CheckAction::class)
        ->autoconfigure()
        ->autowire()
        ->tag('controller.service_arguments');
};
