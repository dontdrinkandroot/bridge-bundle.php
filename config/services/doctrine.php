<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\BridgeBundle\DependencyInjection\DdrBridgeExtension;
use Dontdrinkandroot\BridgeBundle\Service\Health\DoctrineHealthProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(DoctrineHealthProvider::class)
        ->args([service(ManagerRegistry::class)])
        ->tag(DdrBridgeExtension::TAG_HEALTH_PROVIDER);
};
