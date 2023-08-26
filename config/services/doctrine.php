<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\BridgeBundle\Model\Container\Tag;
use Dontdrinkandroot\BridgeBundle\Service\DatabaseModificationService;
use Dontdrinkandroot\BridgeBundle\Service\Health\DoctrineHealthProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(DoctrineHealthProvider::class)
        ->args([service(ManagerRegistry::class)])
        ->tag(Tag::HEALTH_PROVIDER);

    $services->set(DatabaseModificationService::class)
        ->args([service('cache.app')])
        ->tag('doctrine.event_listener', ['event' => 'postPersist'])
        ->tag('doctrine.event_listener', ['event' => 'postUpdate'])
        ->tag('doctrine.event_listener', ['event' => 'postRemove']);
};
