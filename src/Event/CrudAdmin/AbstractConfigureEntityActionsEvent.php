<?php

namespace Dontdrinkandroot\BridgeBundle\Event\CrudAdmin;

use Knp\Menu\ItemInterface;

/**
 * @template T of object
 * @extends AbstractConfigureActionsEvent<T>
 */
class AbstractConfigureEntityActionsEvent extends AbstractConfigureActionsEvent
{
    /**
     * @param class-string<T> $entityClass
     * @param T $entity
     * @param array<string, mixed> $options
     */
    public function __construct(
        string $entityClass,
        public readonly object $entity,
        ItemInterface $item,
        array $options
    ) {
        parent::__construct($entityClass, $item, $options);
    }
}
