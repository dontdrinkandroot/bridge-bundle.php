<?php

namespace Dontdrinkandroot\BridgeBundle\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $password = null;

    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $salt = null;

    /**
     * @param string       $email
     * @param list<string> $roles
     */
    public function __construct(
        #[ORM\Column(type: 'string', length: 320, unique: true)]
        public string $email,

        #[ORM\Column(type: 'json')]
        public array $roles = [],
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return $this->salt;
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
}