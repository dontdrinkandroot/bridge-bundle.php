<?php

namespace Dontdrinkandroot\BridgeBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

abstract class AbstractConfigureNavbarEvent
{
    public function __construct(
        public readonly FactoryInterface $factory,
        public readonly  ItemInterface $item
    ) {
    }
}
