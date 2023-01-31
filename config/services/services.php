<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Command\Encrypt\GenerateKeyCommand;
use Dontdrinkandroot\BridgeBundle\Controller\HealthAction;
use Dontdrinkandroot\BridgeBundle\DependencyInjection\DdrBridgeExtension;
use Dontdrinkandroot\BridgeBundle\Form\Type\FlexDateType;
use Dontdrinkandroot\BridgeBundle\Routing\NestedLoader;
use Dontdrinkandroot\BridgeBundle\Service\EncryptionService;
use Dontdrinkandroot\BridgeBundle\Service\Health\HttpHealthProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(FlexDateType::class)
        ->tag('form.type', ['alias' => 'flexdate']);

    $services->set(NestedLoader::class)
        ->args([
            service('file_locator')
        ])
        ->tag('routing.loader');

    $services->set(EncryptionService::class);

    $services->set(GenerateKeyCommand::class)
        ->args([service(EncryptionService::class)])
        ->tag('console.command');

    $services->set(HealthAction::class)
        ->args([tagged_iterator(DdrBridgeExtension::TAG_HEALTH_PROVIDER)])
        ->public();

    $services->set(HttpHealthProvider::class)
        ->args([service(RequestStack::class)])
        ->tag(DdrBridgeExtension::TAG_HEALTH_PROVIDER);
};
