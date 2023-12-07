<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Menu\Bootstrap5DropdownTrait;
use Dontdrinkandroot\BridgeBundle\Event\ConfigureNavbarLeftEvent;
use Dontdrinkandroot\BridgeBundle\Event\ConfigureNavbarRightEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractNavbarEventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureNavbarLeftEvent::class  => 'onConfigureNavbarLeft',
            ConfigureNavbarRightEvent::class => 'onConfigureNavbarRight',
        ];
    }

    public function onConfigureNavbarLeft(ConfigureNavbarLeftEvent $event): void
    {
    }

    public function onConfigureNavbarRight(ConfigureNavbarRightEvent $event): void
    {
    }
}
