<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestKernelTest extends WebTestCase
{
    public function testIsWorking(): void
    {
        self::bootKernel();
        $this->assertNotNull(self::$kernel);
    }
}
