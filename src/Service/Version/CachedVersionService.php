<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Version;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedVersionService extends VersionService
{
    public function __construct(
        KernelInterface $kernel,
        LoggerInterface $logger,
        private readonly CacheInterface $cache,
    ) {
        parent::__construct($kernel, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion(): string
    {
        return $this->cache->get('ddr.version', function (ItemInterface $cacheItem): string {
            $cacheItem->expiresAfter(60 * 60);

            return parent::getVersion();
        });
    }
}
