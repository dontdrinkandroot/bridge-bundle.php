<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
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
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        if (
            null === $id
            || !is_a($entityClass, UuidEntityInterface::class, true)
        ) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        try {
            $uuid = Uuid::fromBase58($id);
        } catch (InvalidArgumentException $e) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        return $entityManager
            ->getUnitOfWork()
            ->getEntityPersister($entityClass)
            ->load(['uuid' => $uuid], null, null, [], null, 1);
    }
}
