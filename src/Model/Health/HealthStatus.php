<?php

namespace Dontdrinkandroot\BridgeBundle\Model\Health;

use JsonSerializable;

class HealthStatus implements JsonSerializable
{
    /**
     * @param bool $ok
     * @param array<string,mixed> $info
     */
    public function __construct(public bool $ok, public array $info = [])
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->ok,
            'info' => $this->info
        ];
    }
}
