<?php

namespace Dontdrinkandroot\BridgeBundle\Security\Voter;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @template T of object
 * @extends Voter<'LIST'|'CREATE'|'READ'|'UPDATE'|'DELETE',T>
 */
abstract class CrudVoter extends Voter
{
    #[Override]
    protected function supports(string $attribute, $subject): bool
    {
        return is_a($subject, $this->getEntityClass(), true) && $this->supportsAttribute($attribute);
    }

    #[Override]
    public function supportsAttribute(string $attribute): bool
    {
        return null !== CrudOperation::tryFrom($attribute);
    }

    #[Override]
    public function supportsType(string $subjectType): bool
    {
        return 'string' === $subjectType || is_a($subjectType, $this->getEntityClass(), true);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->voteOnCrudOperation(CrudOperation::from($attribute), $subject, $token);
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getEntityClass(): string;

    /**
     * @param string|T  $subject
     */
    protected function voteOnCrudOperation(
        CrudOperation $crudOperation,
        string|object $subject,
        TokenInterface $token
    ): bool {
        return match ($crudOperation) {
            CrudOperation::LIST => $this->isListGranted($token),
            CrudOperation::CREATE => $this->isCreateGranted($token),
            CrudOperation::READ => $this->isReadGranted(
                Asserted::instanceOf($subject, $this->getEntityClass()),
                $token
            ),
            CrudOperation::UPDATE => $this->isUpdateGranted(
                Asserted::instanceOf($subject, $this->getEntityClass()),
                $token
            ),
            CrudOperation::DELETE => $this->isDeleteGranted(
                Asserted::instanceOf($subject, $this->getEntityClass()),
                $token
            ),
        };
    }

    protected function isListGranted(TokenInterface $token): bool
    {
        return false;
    }

    protected function isCreateGranted(TokenInterface $token): bool
    {
        return false;
    }

    /**
     * @param T $entity
     */
    protected function isReadGranted(object $entity, TokenInterface $token): bool
    {
        return false;
    }

    /**
     * @param T $entity
     */
    protected function isUpdateGranted(object $entity, TokenInterface $token): bool
    {
        return false;
    }

    /**
     * @param T $entity
     */
    protected function isDeleteGranted(object $entity, TokenInterface $token): bool
    {
        return false;
    }
}
