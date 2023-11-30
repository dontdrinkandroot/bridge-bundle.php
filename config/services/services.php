<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Command\Encrypt\GenerateKeyCommand;
use Dontdrinkandroot\BridgeBundle\Controller\HealthAction;
use Dontdrinkandroot\BridgeBundle\Controller\ValueResolver\IdEntityArgumentValueResolver;
use Dontdrinkandroot\BridgeBundle\Controller\ValueResolver\UuidEntityArgumentValueResolver;
use Dontdrinkandroot\BridgeBundle\Form\Type\FlexDateType;
use Dontdrinkandroot\BridgeBundle\Model\Container\Tag;
use Dontdrinkandroot\BridgeBundle\Routing\NestedLoader;
use Dontdrinkandroot\BridgeBundle\Service\EncryptionService;
use Dontdrinkandroot\BridgeBundle\Service\Health\HttpHealthProvider;
use Dontdrinkandroot\BridgeBundle\Service\Version\CachedVersionService;
use Dontdrinkandroot\BridgeBundle\Service\Version\VersionServiceInterface;
use Dontdrinkandroot\BridgeBundle\Twig\TwigExtension;
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
        ->args([tagged_iterator(Tag::HEALTH_PROVIDER)])
        ->public();

    $services->set(HttpHealthProvider::class)
        ->args([service(RequestStack::class)])
        ->tag(Tag::HEALTH_PROVIDER);

    $services->set(VersionServiceInterface::class, CachedVersionService::class)
        ->args([
            service('kernel'),
            service('logger'),
            service('cache.app'),
        ]);

    $services->set(TwigExtension::class, TwigExtension::class)
        ->args([
            service(VersionServiceInterface::class)
        ])
        ->tag('twig.extension');

    $services->set(IdEntityArgumentValueResolver::class)
        ->args([
            service('doctrine'),
        ])
        ->tag(Tag::CONTROLLER_ARGUMENT_VALUE_RESOLVER, ['priority' => 101]);

    $services->set(UuidEntityArgumentValueResolver::class)
        ->args([
            service('doctrine'),
        ])
        ->tag(Tag::CONTROLLER_ARGUMENT_VALUE_RESOLVER, ['priority' => 101]);
};
