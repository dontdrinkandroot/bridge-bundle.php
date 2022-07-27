<?php

namespace Dontdrinkandroot\BridgeBundle\Security\Voter;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @template T of object
 */
abstract class CrudVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        return is_a($subject, $this->getEntityClass(), true) && $this->supportsAttribute($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute(string $attribute): bool
    {
        return null !== CrudOperation::tryFrom($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsType(string $subjectType): bool
    {
        return 'string' === $subjectType || is_a($subjectType, $this->getEntityClass(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->voteOnCrudOperation(CrudOperation::from($attribute), $subject);
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getEntityClass(): string;

    /**
     * @param CrudOperation     $crudOperation
     * @param T|class-string<T> $subject
     *
     * @return bool
     */
    protected function voteOnCrudOperation(CrudOperation $crudOperation, string|object $subject): bool
    {
        return match ($crudOperation) {
            CrudOperation::LIST => $this->isListGranted(),
            CrudOperation::CREATE => $this->isCreateGranted(),
            CrudOperation::READ => $this->isReadGranted(Asserted::instanceOf($subject, $this->getEntityClass())),
            CrudOperation::UPDATE => $this->isUpdateGranted(Asserted::instanceOf($subject, $this->getEntityClass())),
            CrudOperation::DELETE => $this->isDeleteGranted(Asserted::instanceOf($subject, $this->getEntityClass())),
        };
    }

    protected function isListGranted(): bool
    {
        return false;
    }

    protected function isCreateGranted(): bool
    {
        return false;
    }

    /**
     * @param T $entity
     *
     * @return bool
     */
    protected function isReadGranted(object $entity): bool
    {
        return false;
    }

    /**
     * @param T $entity
     *
     * @return bool
     */
    protected function isUpdateGranted(object $entity): bool
    {
        return false;
    }

    /**
     * @param T $entity
     *
     * @return bool
     */
    protected function isDeleteGranted(object $entity): bool
    {
        return false;
    }
}
