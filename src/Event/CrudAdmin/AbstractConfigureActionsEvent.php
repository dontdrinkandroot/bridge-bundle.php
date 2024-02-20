<?php

namespace Dontdrinkandroot\BridgeBundle\Event\CrudAdmin;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of object
 */
class AbstractConfigureActionsEvent extends Event
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
