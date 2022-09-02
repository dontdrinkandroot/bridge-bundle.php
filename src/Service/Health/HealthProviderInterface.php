<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Health;

interface HealthProviderInterface
{
    public function getKey(): string;

    public function getValue(): null|object|array|float|int|bool;
}
