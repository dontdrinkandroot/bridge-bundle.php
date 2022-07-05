<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
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
    public function supportsItem(CrudAdminContext $context): bool
    {
        $encodedUuid = RequestAttributes::getId($context->getRequest());
        if (
            null === $encodedUuid
            || !is_a($context->getEntityClass(), UuidEntityInterface::class, true)
        ) {
            return false;
        }

        try {
            Uuid::fromBase58($encodedUuid);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminContext $context): ?object
    {
        $encodedUuid = RequestAttributes::getId($context->getRequest());
        $uuid = Uuid::fromBase58($encodedUuid);
        $entityClass = $context->getEntityClass();
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
