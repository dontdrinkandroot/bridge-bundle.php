<?php

namespace Dontdrinkandroot\BridgeBundle\Twig;

use Dontdrinkandroot\BridgeBundle\Service\Version\VersionServiceInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly VersionServiceInterface $versionService
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ddr_version', $this->getVersion(...)),
        ];
    }

    public function getVersion(): string
    {
        return $this->versionService->getVersion();
    }
}
