<?php

namespace Dontdrinkandroot\BridgeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use function dirname;

class DdrBridgeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
