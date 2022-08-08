<?php

namespace Dontdrinkandroot\BridgeBundle\Model\Security;

use DateTime;

class TooManyResetPasswordRequestsException extends ResetPasswordException
{
    public function __construct(public readonly DateTime $expiry)
    {
        parent::__construct('Das Zurücksetzen wurde bereits angefragt.');
    }
}
