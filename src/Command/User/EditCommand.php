<?php

namespace Dontdrinkandroot\BridgeBundle\Command\User;

use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

/**
 * @template T of \Dontdrinkandroot\BridgeBundle\Entity\User
 */
class EditCommand extends Command
{
    protected static $defaultName = 'ddr:bridge:user:edit';

    /**
     * @param UserRepository<T> $userRepository
     * @param class-string<T>   $userClass
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly TransactionManagerRegistry $transactionManagerRegistry,
        private readonly string $userClass
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->transactionManagerRegistry->getDefault()->transactional(
            fn() => $this->doExecute($input, $output)
        );
    }

    private function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = Asserted::instanceOf($this->getHelper('question'), QuestionHelper::class);

        $email = $questionHelper->ask($input, $output, new Question('Email: '));
        $user = $this->userRepository->findOneByEmail($email);
        if (null === $user) {
            $create = $questionHelper->ask($input, $output, new ConfirmationQuestion('User does not exist. Create?: '));
            if (!$create) {
                return 0;
            }
            $user = $this->userClass::fromEmail($email);
            $this->userRepository->create($user, false);
        }

        $rolesQuestion = new Question(sprintf("Roles (%s): ", implode(',', $user->roles)), implode(',', $user->roles));
        $roles = $questionHelper->ask($input, $output, $rolesQuestion);
        $user->roles = explode(',', $roles);

        $password = $questionHelper->ask($input, $output, (new Question('Password: '))->setHidden(true));
        if (null !== $password && '' !== trim($password)) {
            $user->password = $this->userPasswordHasher->hashPassword($user, $password);
        }

        $output->writeln('Updated.');

        return 0;
    }
}