<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\Security;

use LogicException;

class LogoutAction
{
    public function __invoke(): never
    {
        throw new LogicException('This code should never be reached');
    }
}
