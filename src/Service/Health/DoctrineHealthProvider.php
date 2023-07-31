<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Health;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\BridgeBundle\Model\Health\HealthStatus;
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
    public function getStatus(): HealthStatus
    {
        $overallOk = true;
        $connections = $this->managerRegistry->getConnections();
        $data = [];
        foreach ($connections as $name => $connection) {
            $connectionStatus = $this->getConnectionStatus($name, Asserted::instanceOf($connection, Connection::class));
            $overallOk = $overallOk && $connectionStatus->ok;
            $data[$name] = $connectionStatus->info;
        }

        return new HealthStatus($overallOk, $data);
    }

    public function getConnectionStatus(string $name, Connection $connection): HealthStatus
    {
        $platform = $connection->getDatabasePlatform();
        try {
            $connection = Asserted::instanceOf($connection, Connection::class);
            $connection->executeQuery('SELECT 1')->fetchAllAssociative();
            return new HealthStatus(true, [
                'platform' => $platform::class
            ]);
        } catch (Exception $e) {
            return new HealthStatus(false, [
                'platform' => $platform::class,
                'error' => $e->getMessage()
            ]);
        }
    }
}
