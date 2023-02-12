<?php

namespace Dontdrinkandroot\BridgeBundle\Service;

use DateTime;
use Psr\Cache\CacheItemPoolInterface;

class DatabaseModificationService
{
    final const KEY_LAST_UPDATE = 'ddr_bridge_db_last_update';

    public function __construct(private readonly CacheItemPoolInterface $cache)
    {
    }

    public function updateLastChange(): void
    {
        $cacheItem = $this->cache->getItem(self::KEY_LAST_UPDATE);
        $cacheItem->set((new DateTime())->getTimestamp());
        $this->cache->save($cacheItem);
    }

    /**
     * Get the timestamp of the last database modification or the timestamp when it was first called when no
     * modification has been made yet.
     *
     * @return int
     */
    public function getLastChange(): int
    {
        $cacheItem = $this->cache->getItem(self::KEY_LAST_UPDATE);
        if (!$cacheItem->isHit()) {
            $cacheItem->set((new DateTime())->getTimestamp());
            $this->cache->save($cacheItem);
        }

        return $cacheItem->get();
    }

    public function postPersist(): void
    {
        $this->updateLastChange();
    }

    public function postUpdate(): void
    {
        $this->updateLastChange();
    }

    public function postRemove(): void
    {
        $this->updateLastChange();
    }
}
