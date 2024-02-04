<?php

namespace App\Tests\Config;

use Dontdrinkandroot\BridgeBundle\Service\Mail\MailService;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Dontdrinkandroot\BridgeBundle\Tests\TestApp\Controller\\', '../Controller');
    $services->load('Dontdrinkandroot\BridgeBundle\Tests\TestApp\DataFixtures\\', '../DataFixtures');
    $services->load('Dontdrinkandroot\BridgeBundle\Tests\TestApp\Security\\', '../Security');

    $services->alias(MailServiceInterface::class, MailService::class)
        ->public();
};
