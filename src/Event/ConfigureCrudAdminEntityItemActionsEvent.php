<?php

namespace Dontdrinkandroot\BridgeBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of object
 * @extends AbstractConfigureCrudAdminActionsEvent<T>
 */
class ConfigureCrudAdminEntityItemActionsEvent extends AbstractConfigureCrudAdminActionsEvent
{
}
