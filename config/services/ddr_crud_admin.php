<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Menu\DdrCrudAdminMenuBuilder;
use Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\DontdrinkandrootTemplateProvider;
use Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer\FontAwesome5BooleanRendererProvider;
use Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer\MillisecondsRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Knp\Menu\FactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(DontdrinkandrootTemplateProvider::class, DontdrinkandrootTemplateProvider::class)
        ->tag('ddr_crud_admin.template_provider', ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]);

    $services->set(FontAwesome5BooleanRendererProvider::class, FontAwesome5BooleanRendererProvider::class)
        ->tag('ddr_crud_admin.field_renderer_provider', ['priority' => DdrCrudAdminExtension::PRIORITY_HIGH]);

    $services->set(MillisecondsRendererProvider::class, MillisecondsRendererProvider::class)
        ->tag('ddr_crud_admin.field_renderer_provider', ['priority' => DdrCrudAdminExtension::PRIORITY_HIGH]);

    $services->set(DdrCrudAdminMenuBuilder::class, DdrCrudAdminMenuBuilder::class)
        ->args([
            service(FactoryInterface::class),
            service(AuthorizationCheckerInterface::class),
            service(UrlResolver::class),
            service(EventDispatcherInterface::class)
        ])
        ->tag(
            'knp_menu.menu_builder',
            ['method' => 'createEntityItemActions', 'alias' => 'ddr_crud_admin_entity_item_actions']
        );
};
