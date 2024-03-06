<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\BridgeBundle\Doctrine\Type\FlexDateType;
use Dontdrinkandroot\Common\FlexDate;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdTrait;

#[ORM\Entity]
class ExampleEntity implements EntityInterface
{
    use GeneratedIdTrait;

    public function __construct(
        #[ORM\Column(type: FlexDateType::NAME, nullable: true)]
        public FlexDate $flexDate = new FlexDate(),
    ) {
    }
}
