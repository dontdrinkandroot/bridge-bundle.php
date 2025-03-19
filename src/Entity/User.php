<?php

namespace Dontdrinkandroot\BridgeBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\MappedSuperclass]
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $password = null;

    /**
     * @param string[] $roles
     */
    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
        public string $email,

        #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
        public array $roles = [],
    ) {
    }

    #[Override]
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    #[Override]
    public function getRoles(): array
    {
        return $this->roles;
    }

    #[Override]
    public function getPassword(): ?string
    {
        return $this->password;
    }

    #[Override]
    public function eraseCredentials(): void
    {
        /* Noop */
    }

    #[Override]
    public function __toString(): string
    {
        return $this->email;
    }

    abstract public static function fromEmail(string $email): static;
}
