<?php

namespace Dontdrinkandroot\BridgeBundle\Command\Encrypt;

use Dontdrinkandroot\BridgeBundle\Service\EncryptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKeyCommand extends Command
{
    protected static $defaultName = 'ddr:encrypt:generate-key';

    public function __construct(private EncryptionService $encryptionService)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(bin2hex($this->encryptionService->generateKey()));
        return 0;
    }
}