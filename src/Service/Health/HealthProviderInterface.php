<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Health;

use Dontdrinkandroot\BridgeBundle\Model\Health\HealthStatus;

interface HealthProviderInterface
{
    public function getKey(): string;

    public function getStatus(): HealthStatus;
}
