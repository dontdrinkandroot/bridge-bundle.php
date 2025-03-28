<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\ConfigureListHeaderActionsEvent;
use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\ConfigureListItemActionsEvent;
use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\ConfigureReadHeaderActionsEvent;
use Dontdrinkandroot\BridgeBundle\Model\BootstrapIcon;
use Dontdrinkandroot\BridgeBundle\Model\DdrCrudAdmin\Action;
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
    use MoreDropdownTrait;

    public function __construct(
        protected readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly UrlResolver $urlResolver,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function createListItemActions(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        /** @var class-string<T> $entityClass */
        $entityClass = $options['entityClass'];
        $entity = Asserted::instanceOf($options['entity'], $entityClass);

        $moreDropdown = $this->createMoreDropdown($menu, ['btn-sm']);

        if ($this->authorizationChecker->isGranted(CrudOperation::READ->value, $entity)) {
            $readUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::READ, $entity);
            $moreDropdown->addChild(Action::READ, ['uri' => $readUrl])
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra(ItemExtra::ICON, BootstrapIcon::SEARCH->toClassString(true, ['me-2']));
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::UPDATE->value, $entity)) {
            $updateUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::UPDATE, $entity);
            $moreDropdown->addChild(Action::UPDATE, ['uri' => $updateUrl])
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra(ItemExtra::ICON, BootstrapIcon::PENCIL->toClassString(true, ['me-2']));
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::DELETE->value, $entity)) {
            $deleteUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::DELETE, $entity);
            $moreDropdown->addChild(Action::DELETE, ['uri' => $deleteUrl])
                ->setAttribute('class', 'text-danger')
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra(ItemExtra::ICON, BootstrapIcon::TRASH->toClassString(true, ['me-2']));
        }

        $this->eventDispatcher->dispatch(
            new ConfigureListItemActionsEvent($entityClass, $entity, $menu, $options)
        );

        if (
            null !== ($moreItem = $menu->getChild(Action::MORE))
            && !$moreItem->hasChildren()
        ) {
            $menu->removeChild(Action::MORE);
        }

        return $menu;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function createReadHeaderActions(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        /** @var class-string<T> $entityClass */
        $entityClass = $options['entityClass'];
        $entity = Asserted::instanceOf($options['entity'], $entityClass);

        if ($this->authorizationChecker->isGranted(CrudOperation::UPDATE->value, $entity)) {
            $updateUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::UPDATE, $entity);
            $menu->addChild(Action::UPDATE, ['uri' => $updateUrl])
                ->setAttribute('class', 'btn-primary ddr-btn-icon btn-lg')
                ->setExtra(ItemExtra::LABEL_AS_TITLE_ONLY, true)
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra(ItemExtra::ICON, BootstrapIcon::PENCIL->toClassString(true));
        }

        $moreDropdown = $this->createMoreDropdown($menu, ['btn-lg']);

        if ($this->authorizationChecker->isGranted(CrudOperation::DELETE->value, $entity)) {
            $deleteUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::DELETE, $entity);
            $moreDropdown->addChild(Action::DELETE, ['uri' => $deleteUrl])
                ->setAttribute('title', Action::DELETE)
                ->setAttribute('class', 'text-danger')
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra(ItemExtra::ICON, BootstrapIcon::TRASH->toClassString(true, ['me-2']));
        }

        $this->eventDispatcher->dispatch(new ConfigureReadHeaderActionsEvent($entityClass, $entity, $menu, $options));

        if (
            null !== ($moreItem = $menu->getChild(Action::MORE))
            && !$moreItem->hasChildren()
        ) {
            $menu->removeChild(Action::MORE);
        }

        return $menu;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function createListHeaderActions(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        /** @var class-string<T> $entityClass */
        $entityClass = $options['entityClass'];

        if ($this->authorizationChecker->isGranted(CrudOperation::CREATE->value, $entityClass)) {
            $createUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::CREATE);
            $menu->addChild(Action::CREATE, ['uri' => $createUrl])
                ->setAttribute('class', 'btn-primary ddr-btn-icon btn-lg')
                ->setExtra(ItemExtra::LABEL_AS_TITLE_ONLY, true)
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
                ->setExtra(ItemExtra::ICON, BootstrapIcon::PLUS->toClassString(true));
        }

        $moreDropdown = $this->createMoreDropdown($menu, ['btn-lg']);

        $this->eventDispatcher->dispatch(new ConfigureListHeaderActionsEvent($entityClass, $menu, $options));

        if (
            null !== ($moreItem = $menu->getChild(Action::MORE))
            && !$moreItem->hasChildren()
        ) {
            $menu->removeChild(Action::MORE);
        }

        return $menu;
    }
}
