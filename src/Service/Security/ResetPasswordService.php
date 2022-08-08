<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Security;

use DateInterval;
use DateTime;
use Dontdrinkandroot\BridgeBundle\Entity\User;
use Dontdrinkandroot\BridgeBundle\Model\Security\InvalidResetPasswordTokenException;
use Dontdrinkandroot\BridgeBundle\Model\Security\TooManyResetPasswordRequestsException;
use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Dontdrinkandroot\Common\Asserted;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class ResetPasswordService
{
    private const PASSWORD_REST_PARAMETER_TOKEN = 'token';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CacheItemPoolInterface $cache,
        private readonly MailServiceInterface $mailService,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    /**
     * @throws TooManyResetPasswordRequestsException
     */
    public function requestPasswordReset(string $email, DateInterval $ttl = new DateInterval('P60M')): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null === $user) {
            return false;
        }

        $token = $this->createResetPasswordToken($user, $ttl);
        $resetLink = $this->createResetPasswordLink($token);
        $this->mailService->sendMailMarkdownTemplate(
            [Address::create($email)],
            'Passwort zurücksetzen',
            '@DdrBridge/Security/reset_password.md.twig',
            [
                'reset_link' => $resetLink,
            ]
        );

        return true;
    }

    public function createResetPasswordLink(string $token): string
    {
        return $this->urlGenerator->generate(
            'ddr.bridge.security.reset_password',
            [
                self::PASSWORD_REST_PARAMETER_TOKEN => $token,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @throws InvalidResetPasswordTokenException
     * @throws InvalidArgumentException
     */
    public function parse(Request $request): ?User
    {
        if (!$request->query->has(self::PASSWORD_REST_PARAMETER_TOKEN)) {
            return null;
        }

        $token = Asserted::string($request->query->get(self::PASSWORD_REST_PARAMETER_TOKEN));
        $cacheItem = $this->cache->getItem($this->getResetTokenCacheId($token));
        if (!$cacheItem->isHit()) {
            throw new InvalidResetPasswordTokenException();
        }

        //TODO: We should probably get it from the user provider as it is the user identifier
        $email = Asserted::string($cacheItem->get());

        return $this->userRepository->findOneByEmail($email);
    }

    public function changePassword(User $user, string $password): void
    {
        $this->cache->deleteItem($this->getResetUserCacheId($user));

        $user->password = $this->passwordHasher->hashPassword($user, $password);
        $this->userRepository->flush();
    }

    private function createResetPasswordToken(User $user, DateInterval $ttl): string
    {
        $expiration = (new DateTime())->add($ttl);

        $userCacheItem = $this->cache->getItem($this->getResetUserCacheId($user));
        if ($userCacheItem->isHit()) {
            throw new TooManyResetPasswordRequestsException($expiration);
        }
        $userCacheItem->expiresAt($expiration);
        $userCacheItem->set($expiration);
        $this->cache->save($userCacheItem);

        $token = Uuid::v4()->toBase58();
        $tokenCacheItem = $this->cache->getItem($this->getResetTokenCacheId($token));
        $tokenCacheItem->expiresAt($expiration);
        $tokenCacheItem->set($user->getUserIdentifier());
        $this->cache->save($tokenCacheItem);

        return $token;
    }

    private function getResetTokenCacheId(string $token): string
    {
        return 'reset_password_token_'.$token;
    }

    private function getResetUserCacheId(User $user): string
    {
        return 'reset_password_user_'.$user->getUserIdentifier();
    }
}
