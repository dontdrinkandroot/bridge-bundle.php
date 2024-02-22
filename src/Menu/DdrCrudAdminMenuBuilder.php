<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\ConfigureReadActionsEvent;
use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\ConfigureItemActionsEvent;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @template T of object
 */
class DdrCrudAdminMenuBuilder
{
    public function __construct(
        protected readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly UrlResolver $urlResolver,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function createEntityItemActions(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        /** @var class-string<T> $entityClass */
        $entityClass = $options['entityClass'];
        $entity = Asserted::instanceOf($options['entity'], $entityClass);

        $moreDropdown = $menu->addChild('action.more', ['label' => ''])
            ->setAttribute('class', 'btn-lg ddr-btn-icon')
            ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
            ->setExtra('icon', 'bi bi-fw bi-three-dots-vertical');

        if ($this->authorizationChecker->isGranted(CrudOperation::READ->value, $entity)) {
            $readUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::READ, $entity);
            $moreDropdown->addChild('action.read', ['uri' => $readUrl])
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-search me-2');
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::UPDATE->value, $entity)) {
            $updateUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::UPDATE, $entity);
            $moreDropdown->addChild('action.update', ['uri' => $updateUrl])
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-pencil me-2');
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::DELETE->value, $entity)) {
            $deleteUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::DELETE, $entity);
            $moreDropdown->addChild('action.delete', ['uri' => $deleteUrl])
                ->setAttribute('class', 'text-danger')
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-trash me-2');
        }

        $this->eventDispatcher->dispatch(
            new ConfigureItemActionsEvent($entityClass, $entity, $menu, $options)
        );

        if (!$menu->getChild('action.more')->hasChildren()) {
            $menu->removeChild('action.more');
        }

        return $menu;
    }

    public function createEntityDetailActions(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        /** @var class-string<T> $entityClass */
        $entityClass = $options['entityClass'];
        $entity = Asserted::instanceOf($options['entity'], $entityClass);

        $moreDropdown = $menu->addChild('action.more', ['label' => ''])
            ->setAttribute('class', 'btn-lg ddr-btn-icon')
            ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
            ->setExtra('icon', 'bi bi-fw bi-three-dots-vertical');

        if ($this->authorizationChecker->isGranted(CrudOperation::UPDATE->value, $entity)) {
            $updateUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::UPDATE, $entity);
            $menu->addChild('action.update', ['uri' => $updateUrl, 'label' => ''])
                ->setAttribute('class', 'btn-primary ddr-btn-icon btn-lg')
                ->setAttribute('title', 'action.update')
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-pencil');
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::DELETE->value, $entity)) {
            $deleteUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::DELETE, $entity);
            $moreDropdown->addChild('action.delete', ['uri' => $deleteUrl])
                ->setAttribute('title', 'action.delete')
                ->setAttribute('class', 'text-danger')
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-trash me-2');
        }

        $this->eventDispatcher->dispatch(new ConfigureReadActionsEvent($entityClass, $entity, $menu, $options));

        if (!$menu->getChild('action.more')->hasChildren()) {
            $menu->removeChild('action.more');
        }

        return $menu;
    }
}
