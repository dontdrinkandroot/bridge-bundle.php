<?php

use ApiPlatform\Symfony\Bundle\ApiPlatformBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    ApiPlatformBundle::class => ['all' => true],
    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class => ['all' => true],
    KnpMenuBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['test' => true],
    Liip\TestFixturesBundle\LiipTestFixturesBundle::class => ['test' => true],
    Dontdrinkandroot\DoctrineBundle\DdrDoctrineBundle::class => ['all' => true],
    Dontdrinkandroot\ApiPlatformBundle\DdrApiPlatformBundle::class => ['all' => true],
    Dontdrinkandroot\CrudAdminBundle\DdrCrudAdminBundle::class => ['all' => true],
    Dontdrinkandroot\BootstrapBundle\DdrBootstrapBundle::class => ['all' => true],
    Dontdrinkandroot\BridgeBundle\DdrBridgeBundle::class => ['all' => true],
];
