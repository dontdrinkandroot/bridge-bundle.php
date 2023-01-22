<?php

namespace Dontdrinkandroot\BridgeBundle\Repository\User;

use Dontdrinkandroot\BridgeBundle\Entity\User;
use Dontdrinkandroot\DoctrineBundle\Repository\TransactionalCrudRepository;

/**
 * @template T of User
 * @extends TransactionalCrudRepository<T>
 */
class UserRepository extends TransactionalCrudRepository
{
    /**
     * @param string $email
     *
     * @return T|null
     */
    public function findOneByEmail(string $email): ?object
    {
        return $this->findOneBy(['email' => $email]);
    }
}
