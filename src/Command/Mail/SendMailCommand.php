<?php

namespace Dontdrinkandroot\BridgeBundle\Command\Mail;

use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Address;

#[AsCommand(name: 'ddr:bridge:mail:send', description: 'Send simple email message')]
class SendMailCommand extends Command
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private SymfonyStyle $io;

    public function __construct(
        private readonly MailServiceInterface $mailService
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addOption('to', null, InputOption::VALUE_REQUIRED, 'The to address of the message')
            ->addOption('subject', null, InputOption::VALUE_REQUIRED, 'The subject of the message')
            ->addOption('markdown', null, InputOption::VALUE_REQUIRED, 'The body of the message provided in markdown');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Send Email');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->mailService->sendMailMarkdown(
            to: [Address::create($input->getOption('to'))],
            subject: $input->getOption('subject'),
            markdown: $input->getOption('markdown')
        );

        $this->io->success('Email was successfully sent.');

        return Command::SUCCESS;
    }


    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $options = ['to', 'subject', 'markdown'];
        foreach ($options as $option) {
            $value = $input->getOption($option);
            if (null === $value) {
                $input->setOption($option, $this->io->ask(sprintf('%s', ucfirst($option))));
            }
        }
    }
}
