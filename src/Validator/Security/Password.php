<?php

namespace Dontdrinkandroot\BridgeBundle\Validator\Security;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Password extends Constraint
{
    public const MESSAGE_TO_SHORT = 'Das Passwort muss mindestens 8 Zeichen lang sein.';
    public const MESSAGE_CHARACTER_REQUIREMENTS = 'Das Passwort muss mindestens drei der folgenden Anforderungen erfüllen: Mindestens ein Kleinbuchstabe, Mindestens ein Großbuchstabe, Mindestens eine Zahl, Mindestens ein Sonderzeichen (ohne Währungssymbole).';
}
