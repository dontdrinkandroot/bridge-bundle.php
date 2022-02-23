<?php

namespace Dontdrinkandroot\BridgeBundle\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        // TODO: Implement getPassword() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        /* Noop */
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserIdentifier(): string {

    }
}
