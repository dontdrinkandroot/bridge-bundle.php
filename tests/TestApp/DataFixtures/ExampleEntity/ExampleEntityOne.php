<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\DataFixtures\ExampleEntity;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity\ExampleEntity;

class ExampleEntityOne extends Fixture
{
    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $entity = new ExampleEntity();
        $manager->persist($entity);
        $manager->flush();
        $this->addReference(self::class, $entity);
    }
}
