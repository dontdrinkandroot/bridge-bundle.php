<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BridgeBundle\Event\ConfigureCrudAdminEntityItemActionsEvent;
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

        if ($this->authorizationChecker->isGranted(CrudOperation::READ->value, $entity)) {
            $readUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::READ, $entity);
            $menu->addChild('action.read', ['uri' => $readUrl])
                ->setExtra('translation_domain', 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-search me-2');
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::UPDATE->value, $entity)) {
            $updateUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::UPDATE, $entity);
            $menu->addChild('action.update', ['uri' => $updateUrl])
                ->setExtra('translation_domain', 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-pencil me-2');
        }

        if ($this->authorizationChecker->isGranted(CrudOperation::DELETE->value, $entity)) {
            $deleteUrl = $this->urlResolver->resolveUrl($entityClass, CrudOperation::DELETE, $entity);
            $menu->addChild('action.delete', ['uri' => $deleteUrl])
                ->setAttribute('class', 'text-danger')
                ->setExtra('translation_domain', 'DdrCrudAdmin')
                ->setExtra('icon', 'bi bi-fw bi-trash me-2');
        }

        $this->eventDispatcher->dispatch(new ConfigureCrudAdminEntityItemActionsEvent($entityClass, $entity, $menu, $options));

        return $menu;
    }
}
