<?php

namespace Dontdrinkandroot\BridgeBundle\Model\Health;

use JsonSerializable;
use Override;

class HealthStatus implements JsonSerializable
{
    /**
     * @param array<string,mixed> $info
     */
    public function __construct(public bool $ok, public array $info = [])
    {
    }

    /**
     * @return array<string,mixed>
     */
    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->ok,
            'info' => $this->info
        ];
    }
}
