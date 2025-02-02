<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\EntityNotFoundException;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidIdentifiedInterface;
use InvalidArgumentException;
use Override;
use Symfony\Component\Uid\Uuid;

class UuidEntityItemProvider implements ItemProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    #[Override]
    public function provideItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        if (
            null === $id
            || !is_a($entityClass, UuidIdentifiedInterface::class, true)
        ) {
            return null;
        }

        try {
            $uuid = Uuid::fromBase58($id);
        } catch (InvalidArgumentException) {
            return null;
        }

        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }

        $entity = $entityManager
            ->getUnitOfWork()
            ->getEntityPersister($entityClass)
            ->load(['uuid' => $uuid], null, null, [], null, 1)
        ?? throw new EntityNotFoundException($entityClass, $crudOperation, $id);

        return $entity;
    }
}
