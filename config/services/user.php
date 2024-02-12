<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Command\User\EditCommand;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginAction;
use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(UserRepository::class, UserRepository::class)
        ->autoconfigure()
        ->autowire()
        ->arg('$entityClass', param('ddr.bridge_bundle.user.class'));

    $services->set(EditCommand::class, EditCommand::class)
        ->autoconfigure()
        ->autowire()
        ->arg('$userClass', param('ddr.bridge_bundle.user.class'))
        ->tag('console.command');

    $services->set(LoginAction::class)
        ->autoconfigure()
        ->autowire()
        ->tag('controller.service_arguments');
};
