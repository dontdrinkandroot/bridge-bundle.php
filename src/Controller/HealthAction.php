<?php

namespace Dontdrinkandroot\BridgeBundle\Controller;

use Dontdrinkandroot\BridgeBundle\Service\Health\HealthProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        foreach ($this->healthProviders as $healthProvider) {
            $value = $healthProvider->getValue();
            if (null !== $value) {
                $data[$healthProvider->getKey()] = $value;
            }
        }

        return new JsonResponse($data);
    }
}
