<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BridgeBundle\Event\ConfigureNavbarLeftEvent;
use Dontdrinkandroot\BridgeBundle\Event\ConfigureNavbarRightEvent;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class NavbarBuilder
{
    public function __construct(private FactoryInterface $factory, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function createLeft(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $this->eventDispatcher->dispatch(new ConfigureNavbarLeftEvent($this->factory, $menu));

        return $menu;
    }

    public function createRight(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $this->eventDispatcher->dispatch(new ConfigureNavbarRightEvent($this->factory, $menu));

        return $menu;
    }
}
