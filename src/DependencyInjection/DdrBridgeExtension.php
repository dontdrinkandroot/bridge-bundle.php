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
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.yaml');

        $phpLoader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('DdrCrudAdminBundle', $bundles)) {
            $loader->load('ddr_crud_admin.yaml');
        }

        if (array_key_exists('DdrDoctrineBundle', $bundles) && array_key_exists('DdrCrudAdminBundle', $bundles)) {
            $loader->load('ddr_crud_admin_ddr_doctrine.yaml');
        }

        if (array_key_exists('KnpMenuBundle', $bundles)) {
            $loader->load('knp_menu.yaml');
        }

        $userConfig = $config['user'] ?? null;
        if (null !== $userConfig) {
            $container->setParameter(
                'ddr.bridge_bundle.user.class',
                $userConfig['class']
            );

            $phpLoader->load('user.php');
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
