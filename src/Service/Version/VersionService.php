<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Version;

use Dontdrinkandroot\Common\Asserted;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

class VersionService implements VersionServiceInterface
{
    public function __construct(
        protected readonly KernelInterface $kernel,
        protected readonly LoggerInterface $logger,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion(): string
    {
        $version = $this->getVersionFromFile();
        if (null !== $version) {
            return $version;
        }

        $version = $this->getVersionFromGit();

        return $version ?? 'n/a';
    }

    private function getVersionFromFile(): ?string
    {
        $versionFile = $this->kernel->getProjectDir() . '/version.txt';
        if (file_exists($versionFile)) {
            return trim(Asserted::string(file_get_contents($versionFile)));
        }

        $this->logger->info('No version file found');
        return null;
    }

    private function getVersionFromGit(): ?string
    {
        $process = new Process(['git', 'log', '-1', '--pretty=format:%cd-dev-%h', '--date=format:%y.%m.%d%H%M']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->logger->error('Executing git log failed: ' . $process->getErrorOutput());

            return null;
        }

        return $process->getOutput();
    }
}
