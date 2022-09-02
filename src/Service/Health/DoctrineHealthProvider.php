<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Health;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;

class DoctrineHealthProvider implements HealthProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return 'database';
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): array
    {
        $data = [];
        $connections = $this->managerRegistry->getConnections();
        foreach ($connections as $name => $connection) {
            $connection = Asserted::instanceOf($connection, Connection::class);
            $connection->executeQuery('SELECT 1')->fetchAllAssociative();
            $platform = Asserted::notNull($connection->getDatabasePlatform());
            $data[$name] = get_class($platform);
        }

        return $data;
    }
}
