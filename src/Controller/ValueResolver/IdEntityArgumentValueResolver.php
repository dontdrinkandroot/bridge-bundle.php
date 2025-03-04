<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\ValueResolver;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class IdEntityArgumentValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) {
    }

    /**
     * @return iterable<EntityInterface>
     */
    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (
            !$request->attributes->has('id')
            || null === ($type = $argument->getType())
            || !is_a($type, EntityInterface::class, true)
            || null === ($manager = $this->managerRegistry->getManagerForClass($type))
        ) {
            return [];
        }

        $manager = Asserted::instanceOf($manager, EntityManager::class);
        $entity = $manager->find($type, $request->attributes->get('id'));

        if (null === $entity) {
            return [];
        }

        return [$entity];
    }
}
