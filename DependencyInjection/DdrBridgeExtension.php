<?php

namespace Dontdrinkandroot\BridgeBundle\DependencyInjection;

use Dontdrinkandroot\BridgeBundle\Doctrine\Type\FlexDateType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrBridgeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));
        $loader->load('services.yaml');

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('DdrCrudAdminBundle', $bundles)) {
            $loader->load('ddr_crud_admin.yaml');
        }

        if (array_key_exists('DdrDoctrineBundle', $bundles) && array_key_exists('DdrCrudAdminBundle', $bundles)) {
            $loader->load('ddr_crud_admin_ddr_doctrine.yaml');
        }

        if (array_key_exists(
            '
        /**
         * {@inheritdoc}
         */KnpMenuBundle',
            $bundles
        )) {
            $loader->load('knp_menu.yaml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');
        assert(is_array($bundles));
        if (array_key_exists('DoctrineBundle', $bundles)) {
            /* Register flexdate type */
            $container->prependExtensionConfig(
                'doctrine',
                [
                    'dbal' => [
                        'types' => [
                            'flexdate' => FlexDateType::class,
                        ],
                    ],
                ]
            );
        }
    }
}
