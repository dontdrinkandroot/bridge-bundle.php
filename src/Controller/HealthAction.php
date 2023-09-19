<?php

namespace Dontdrinkandroot\BridgeBundle\Controller;

use Dontdrinkandroot\BridgeBundle\Service\Health\HealthProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HealthAction
{
    /**
     * @param iterable<HealthProviderInterface> $healthProviders
     */
    public function __construct(private readonly iterable $healthProviders)
    {
    }

    public function __invoke(Request $request): Response
    {
        $data = [];
        $overallOk = true;
        foreach ($this->healthProviders as $healthProvider) {
            $status = $healthProvider->getStatus();
            $overallOk = $overallOk && $status->ok;
            $data[$healthProvider->getKey()] = $status;
        }

        return new JsonResponse([
            'ok' => $overallOk,
            'services' => $data
        ], $overallOk ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
