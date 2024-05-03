<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\BridgeBundle\Validator\FlexDate;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdTrait;

#[ORM\Entity]
class ExampleEntity implements EntityInterface
{
    use GeneratedIdTrait;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
        #[FlexDate]
        public ?string $flexDate = null
    ) {
    }
}
