<?php

namespace Dontdrinkandroot\BridgeBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of object
 */
class AbstractConfigureCrudAdminActionsEvent extends Event
{
    /**
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly object $entity,
        public readonly ItemInterface $item,
        public readonly array $options
    ) {
    }
}