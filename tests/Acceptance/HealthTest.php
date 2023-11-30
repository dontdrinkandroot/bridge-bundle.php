<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthTest extends WebTestCase
{
    public function testHealthWorking(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/_health');
        self::assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        self::assertIsString($content);
        $decodedContent = json_decode($content, true);
        self::assertEquals([
            'ok' => true,
            'services' => [
                'http' => [
                    'ok' => true,
                    'info' => [
                        'base_uri' => 'http://localhost',
                        'host' => 'localhost',
                        'port' => 80,
                        'scheme' => 'http',
                        'secure' => false
                    ]
                ],
                'database' => [
                    'ok' => true,
                    'info' => [
                        'default' => ['platform' => 'Doctrine\DBAL\Platforms\SqlitePlatform']
                    ]
                ]
            ]
        ], $decodedContent);
    }
}
