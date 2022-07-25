<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

class UuidEntityItemProvider implements ItemProviderInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsItem(CrudOperation $crudOperation, string $entityClass, mixed $id): bool
    {
        if (
            null === $id
            || !is_a($entityClass, UuidEntityInterface::class, true)
        ) {
            return false;
        }

        try {
            Uuid::fromBase58($id);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        $uuid = Uuid::fromBase58($id);
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        return $entityManager
            ->getUnitOfWork()
            ->getEntityPersister($entityClass)
            ->load(['uuid' => $uuid], null, null, [], null, 1);
    }
}
