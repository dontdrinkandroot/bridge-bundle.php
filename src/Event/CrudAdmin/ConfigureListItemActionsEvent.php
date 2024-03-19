<?php

namespace Dontdrinkandroot\BridgeBundle\Event\CrudAdmin;

use Dontdrinkandroot\BridgeBundle\Event\CrudAdmin\AbstractConfigureEntityActionsEvent;
use Dontdrinkandroot\Common\Asserted;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of object
 * @extends AbstractConfigureEntityActionsEvent<T>
 */
class ConfigureListItemActionsEvent extends AbstractConfigureEntityActionsEvent
{
}
