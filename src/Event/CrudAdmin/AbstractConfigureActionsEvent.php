<?php

namespace Dontdrinkandroot\BridgeBundle\Event\CrudAdmin;

use Dontdrinkandroot\BridgeBundle\Model\DdrCrudAdmin\Action;
use Dontdrinkandroot\Common\Asserted;
use Knp\Menu\ItemInterface;

/**
 * @template T of object
 */
class AbstractConfigureActionsEvent
{
    /**
     * @param class-string<T> $entityClass
     * @param array<string, mixed> $options
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly ItemInterface $item,
        public readonly array $options
    ) {
    }

    public function getMoreDropdown(): ?ItemInterface
    {
        return $this->item->getChild(Action::MORE);
    }

    public function fetchMoreDropdown(): ItemInterface
    {
        return Asserted::notNull($this->getMoreDropdown(), 'More dropdown not found');
    }
}
