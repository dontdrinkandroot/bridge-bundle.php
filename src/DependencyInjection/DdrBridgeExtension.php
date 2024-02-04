<?php

namespace Dontdrinkandroot\BridgeBundle\DependencyInjection;

use Dontdrinkandroot\BridgeBundle\Command\Mail\SendMailCommand;
use Dontdrinkandroot\BridgeBundle\Doctrine\Type\FlexDateType;
use Dontdrinkandroot\BridgeBundle\Model\Container\Tag;
use Dontdrinkandroot\BridgeBundle\Service\Health\HealthProviderInterface;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailService;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Dontdrinkandroot\Common\Asserted;
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

        $container
            ->registerForAutoconfiguration(HealthProviderInterface::class)
            ->addTag(Tag::HEALTH_PROVIDER);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.php');

        $bundles = Asserted::array($container->getParameter('kernel.bundles'));

        if (array_key_exists('DdrCrudAdminBundle', $bundles)) {
            $loader->load('ddr_crud_admin.php');
        }

        if (array_key_exists('DdrDoctrineBundle', $bundles)) {
            $loader->load('doctrine.php');
        }

        if (
            array_key_exists('DdrDoctrineBundle', $bundles)
            && array_key_exists('DdrCrudAdminBundle', $bundles)
        ) {
            $loader->load('ddr_crud_admin_ddr_doctrine.php');
        }

        if (array_key_exists('KnpMenuBundle', $bundles)) {
            $loader->load('knp_menu.php');
        }

        $userConfig = $config['user'] ?? null;
        if (null !== $userConfig && true === $userConfig['enabled']) {
            $this->configureUser($userConfig, $container, $loader);
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
                            FlexDateType::NAME => FlexDateType::class,
                        ],
                    ],
                ]
            );
        }
    }

    /**
     * @param array{address: array{from: string, reply_to: string|null}} $mailConfig
     */
    public function configureMail(array $mailConfig, ContainerBuilder $container): void
    {
        $addressConfig = $mailConfig['address'];
        $addressFrom = $addressConfig['from'];
        $addressReplyTo = $addressConfig['reply_to'] ?? null;
        $definitionAddressFrom = $container->setDefinition(
            'ddr.bridge.mail.address.from',
            (new Definition(Address::class, [$addressFrom]))
                ->setFactory([Address::class, 'create'])
        );
        $definitionAddressReplyTo = null;
        if (null !== $addressReplyTo) {
            $definitionAddressReplyTo = $container->setDefinition(
                'ddr.bridge.mail.address.reply_to',
                (new Definition(Address::class, [$addressReplyTo]))
                    ->setFactory([Address::class, 'create'])
            );
        }
        $definitionMailService = $container->setDefinition(
            MailService::class,
            new Definition(MailService::class, [
                new Reference(MailerInterface::class),
                new Reference(Environment::class),
                $definitionAddressFrom,
                $definitionAddressReplyTo
            ])
        );
        $container->setAlias(MailServiceInterface::class, MailService::class);
        $container->setDefinition(
            SendMailCommand::class,
            new Definition(SendMailCommand::class, [$definitionMailService])
        )->addTag('console.command');
    }

    /**
     * @param array{class: string, reset_password: bool} $userConfig
     *
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
