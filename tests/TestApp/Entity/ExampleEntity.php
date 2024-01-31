<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdTrait;

#[ORM\Entity]
class ExampleEntity implements EntityInterface
{
    use GeneratedIdTrait;
}
