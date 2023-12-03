<?php

namespace Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTemplateTest extends WebTestCase
{
    public function testBaseTemplate(): void
    {
        $client = self::createClient();
        $client->request('GET', '/base_template');
        self::assertResponseIsSuccessful();
        self::assertIsString($content = $client->getResponse()->getContent());
    }
}
