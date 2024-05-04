<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Health;

use Dontdrinkandroot\BridgeBundle\Model\Health\HealthStatus;
use Override;
use Symfony\Component\HttpFoundation\RequestStack;

class HttpHealthProvider implements HealthProviderInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    #[Override]
    public function getKey(): string
    {
        return 'http';
    }

    #[Override]
    public function getStatus(): HealthStatus
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return new HealthStatus(false, ['error' => 'No request available']);
        }

        return new HealthStatus(true, [
            'base_uri' => $request->getSchemeAndHttpHost(),
            'host' => $request->getHost(),
            'port' => $request->getPort(),
            'scheme' => $request->getScheme(),
            'secure' => $request->isSecure()
        ]);
    }
}
