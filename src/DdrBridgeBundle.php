<?php

namespace Dontdrinkandroot\BridgeBundle;

use Override;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use function dirname;

class DdrBridgeBundle extends Bundle
{
    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
