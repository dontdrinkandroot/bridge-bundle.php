<?php

namespace Dontdrinkandroot\BridgeBundle\Validator\Security;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Password extends Constraint
{
    final public const MESSAGE_TOO_SHORT = 'password.too_short';
    final public const MESSAE_MISSING_CHARACTER_CLASS = 'password.missing_character_class';
}
