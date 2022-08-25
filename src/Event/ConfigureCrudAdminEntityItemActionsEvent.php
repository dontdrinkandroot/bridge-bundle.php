<?php

namespace Dontdrinkandroot\BridgeBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of object
 */
class ConfigureCrudAdminEntityItemActionsEvent extends Event
{
    /**
     * @param class-string<T> $entityClass
     * @param T               $entity
     * @param ItemInterface   $item
     * @param array           $options
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly object $entity,
        public readonly ItemInterface $item,
        public readonly array $options
    ) {
    }
}
