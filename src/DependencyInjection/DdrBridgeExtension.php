<?php

namespace Dontdrinkandroot\BridgeBundle\DependencyInjection;

use Dontdrinkandroot\BridgeBundle\Command\Mail\SendMailCommand;
use Dontdrinkandroot\BridgeBundle\Doctrine\Type\FlexDateType;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailService;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

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
            $phpLoader->load('ddr_crud_admin.php');
        }

        if (
            array_key_exists('DdrDoctrineBundle', $bundles)
            && array_key_exists('DdrCrudAdminBundle', $bundles)
        ) {
            $loader->load('ddr_crud_admin_ddr_doctrine.yaml');
        }

        if (array_key_exists('KnpMenuBundle', $bundles)) {
            $loader->load('knp_menu.yaml');
        }

        $userConfig = $config['user'] ?? null;
        if (null !== $userConfig) {
            $this->configureUser($userConfig, $container, $phpLoader);
        }

        $mailConfig = $config['mail'] ?? null;
        if (null !== $mailConfig) {
            $this->configureMail($mailConfig, $container);
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

    /**
     * @param array{address: array{from: string, reply_to: string|null}} $mailConfig
     * @param ContainerBuilder                                           $container
     *
     * @return void
     */
    public function configureMail(array $mailConfig, ContainerBuilder $container): void
    {
        $addressConfig = $mailConfig['address'];
        $addressFrom = $addressConfig['from'];
        $addressReplyTo = $addressConfig['reply_to'] ?? $addressFrom;
        $definitionAddressFrom = $container->setDefinition(
            'ddr.bridge.mail.address.from',
            (new Definition(Address::class, [$addressFrom]))
                ->setFactory([Address::class, 'fromString'])
        );
        $definitionAddressReplyTo = $container->setDefinition(
            'ddr.bridge.mail.address.reply_to',
            (new Definition(Address::class, [$addressReplyTo]))
                ->setFactory([Address::class, 'fromString'])
        );
        $definitionMailService = $container->setDefinition(
            MailServiceInterface::class,
            new Definition(MailService::class, [
                new Reference(MailerInterface::class),
                new Reference(Environment::class),
                $definitionAddressFrom,
                $definitionAddressReplyTo
            ])
        );
        $container->setDefinition(
            SendMailCommand::class,
            new Definition(SendMailCommand::class, [$definitionMailService])
        )->addTag('console.command');
    }

    /**
     * @param array{class: string, reset_password: bool} $userConfig
     * @param ContainerBuilder                           $container
     * @param Loader\PhpFileLoader                       $phpLoader
     *
     * @return void
     * @throws Exception
     */
    public function configureUser(array $userConfig, ContainerBuilder $container, Loader\PhpFileLoader $phpLoader): void
    {
        $container->setParameter('ddr.bridge_bundle.user.class', $userConfig['class']);
        $phpLoader->load('user.php');
        if ($userConfig['reset_password']) {
            $phpLoader->load('reset_password.php');
        }
    }
}
