<?php

namespace Dontdrinkandroot\BridgeBundle\Tests;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Dontdrinkandroot\Common\Asserted;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * @param class-string[] $classNames
     */
    protected static function loadFixtures(array $classNames = []): ReferenceRepository
    {
        return self::getService(DatabaseToolCollection::class)->get()
            ->loadFixtures($classNames)
            ->getReferenceRepository();
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    protected static function getService(string $class): object
    {
        $service = self::getContainer()->get($class);
        self::assertInstanceOf($class, $service);
        return $service;
    }

    protected static function logIn(KernelBrowser $client, string $identifier): void
    {
        $userProvider = Asserted::instanceOf(
            self::getContainer()->get(UserProviderInterface::class),
            UserProviderInterface::class
        );
        $user = $userProvider->loadUserByIdentifier($identifier);
        $client->loginUser($user);
    }
}
