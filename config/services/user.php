<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Command\User\UpdateCommand;
use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(UserRepository::class, UserRepository::class)
        ->autoconfigure()
        ->autowire()
        ->arg('$entityClass', param('ddr.bridge_bundle.user.class'));

    $services->set(UpdateCommand::class, UpdateCommand::class)
        ->autoconfigure()
        ->autowire();
};
