<?php

namespace Dontdrinkandroot\BridgeBundle\Model\Security;

class InvalidResetPasswordTokenException extends ResetPasswordException
{
    public function __construct()
    {
        parent::__construct('Der Token zum Zurücksetzen des Passworts ist ungültig oder abgelaufen.');
    }
}
