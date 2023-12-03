<?php

namespace App\Tests\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Dontdrinkandroot\BridgeBundle\Tests\TestApp\Controller\\', '../Controller');
};
