<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\Security\LoginLink;

use Dontdrinkandroot\BridgeBundle\Entity\User;
use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Translation\TranslatableMessage;

class RequestAction extends AbstractController
{
    /**
     * @param UserRepository<User> $userRepository
     */
    public function __construct(
        private readonly LoginLinkHandlerInterface $loginLinkHandler,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserRepository $userRepository,
        private readonly MailServiceInterface $mailService,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (null !== ($user = $this->tokenStorage->getToken()?->getUser())) {
            return $this->redirectToRoute('app.index');
        }

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->getForm();

        $success = false;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $user = $this->userRepository->findOneByEmail($email);
            if (null !== $user) {
                $loginLinkDetails = $this->loginLinkHandler->createLoginLink($user);
                $loginLink = $loginLinkDetails->getUrl();
                $this->mailService->sendMailMarkdownTemplate(
                    to: new Address($user->email),
                    subject: 'Login Link',
                    template: '@DdrBridge/Security/login_link.mail.md.twig',
                    templateParameters: [
                        'user' => $user,
                        'link' => $loginLink,
                    ]
                );
            }
            $this->addFlash(
                'success',
                new TranslatableMessage('login_link.flash.success', [], 'ddr_security')
            );
            $success = true;
        }

        return $this->render('@DdrBridge/Security/request_login_link.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }
}
