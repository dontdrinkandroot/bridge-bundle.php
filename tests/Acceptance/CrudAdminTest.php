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

    public function testCreateWithValidationErrors(): void
    {
        $client = static::createClient();
        self::logIn($client, 'admin');
        $client->request('GET', '/example_entities/__NEW__/edit');
        self::assertResponseIsSuccessful();
        $crawler = $client->submitForm(
            'Save',
            [
                'example_entity[flexDate][month]' => '2',
                'example_entity[flexDate][day]' => '31',
            ]
        );
        self::assertResponseIsSuccessful();
        // TODO: Validate error message

        $crawler = $client->submitForm(
            'Save',
            [
                'example_entity[flexDate][year]' => '2021',
                'example_entity[flexDate][month]' => '2',
                'example_entity[flexDate][day]' => '31',
            ]
        );
        self::assertResponseIsSuccessful();
        // TODO: Validate error message
    }
}
