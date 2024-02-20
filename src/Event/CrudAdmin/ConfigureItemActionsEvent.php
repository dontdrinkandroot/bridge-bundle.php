<?php

namespace Dontdrinkandroot\BridgeBundle\Event\CrudAdmin;

use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\AbstractConfigureActionsEvent;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of object
 * @extends AbstractConfigureActionsEvent<T>
 */
class ConfigureItemActionsEvent extends AbstractConfigureActionsEvent
{
}
