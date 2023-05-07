<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Version;

use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Cache\CacheInterface;

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
        return $this->cache->get('ddr.version', function (CacheItem $cacheItem): string {
            $cacheItem->expiresAfter(60 * 60);

            return parent::getVersion();
        });
    }
}
