<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\ValueResolver;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidIdentifiedInterface;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Uuid;

class UuidEntityArgumentValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) {
    }

    /**
     * @return iterable<UuidIdentifiedInterface>
     */
    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (
            !$request->attributes->has('uuid')
            || null === ($type = $argument->getType())
            || !is_a($type, UuidIdentifiedInterface::class, true)
            || null === ($manager = $this->managerRegistry->getManagerForClass($type))
        ) {
            return [];
        }

        $uuidString = $request->attributes->get('uuid');
        $uuid = Uuid::fromString($uuidString);

        /** @var class-string $type */
        $type = $argument->getType();
        $manager = Asserted::instanceOf($this->managerRegistry->getManagerForClass($type), EntityManager::class);
        $entity = $manager->createQueryBuilder()
            ->select('entity')
            ->from($type, 'entity')
            ->where('entity.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            return [];
        }

        return [$entity];
    }
}
