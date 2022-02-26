<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
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
        $uuid = RequestAttributes::getId($context->getRequest());
        return null !== $uuid
            && Uuid::isValid($uuid)
            && is_a($context->getEntityClass(), UuidEntityInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminContext $context): ?object
    {
        $id = RequestAttributes::getId($context->getRequest());
        $entityClass = $context->getEntityClass();
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        return $entityManager
            ->getUnitOfWork()
            ->getEntityPersister($entityClass)
            ->load(['uuid' => $id], null, null, [], null, 1);
    }
}
