<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Health;

use Symfony\Component\HttpFoundation\RequestStack;

class HttpHealthProvider implements HealthProviderInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return 'http';
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): null|array
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return null;
        }

        return [
            'base_uri' => $request->getSchemeAndHttpHost(),
            'host' => $request->getHost(),
            'port' => $request->getPort(),
            'scheme' => $request->getScheme(),
            'secure' => $request->isSecure()
        ];
    }
}
