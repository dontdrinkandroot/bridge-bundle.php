<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\Acceptance;

use Dontdrinkandroot\BridgeBundle\Tests\TestApp\DataFixtures\ExampleEntity\ExampleEntityOne;
use Dontdrinkandroot\BridgeBundle\Tests\WebTestCase;

class CrudAdminTest extends WebTestCase
{
    public function testList(): void
    {
        $client = static::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntityOne::class]);
        self::logIn($client, 'user');
        $client->request('GET', '/example_entities/');
        self::assertResponseIsSuccessful();
    }
}
