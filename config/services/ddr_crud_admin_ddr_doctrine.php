<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\UuidEntityIdProvider;
use Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\UuidEntityItemProvider;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(UuidEntityItemProvider::class)
        ->args([
            service('doctrine')
        ])
        ->tag(DdrCrudAdminExtension::TAG_ITEM_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_HIGH]);

    $services->set(UuidEntityIdProvider::class)
        ->args([
            service('doctrine')
        ])
        ->tag(DdrCrudAdminExtension::TAG_ID_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_HIGH]);
};
