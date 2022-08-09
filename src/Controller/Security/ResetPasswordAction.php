<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\Security;

use Dontdrinkandroot\BridgeBundle\Model\Security\InvalidResetPasswordTokenException;
use Dontdrinkandroot\BridgeBundle\Model\Security\TooManyResetPasswordRequestsException;
use Dontdrinkandroot\BridgeBundle\Service\Security\ResetPasswordService;
use Dontdrinkandroot\BridgeBundle\Validator\Security\Password;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordAction extends AbstractController
{
    public function __construct(
        private readonly ResetPasswordService $resetPasswordService,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = null;
        try {
            $user = $this->resetPasswordService->parse($request);
        } catch (InvalidResetPasswordTokenException $e) {
            $this->addFlash(
                'error',
                $this->translator->trans(id: 'reset_password.token_invalid', domain: 'ddr_security')
            );
            return $this->redirectToRoute('ddr.bridge.security.reset_password');
        }

        $requestForm = $this->createFormBuilder()
            ->add('email', EmailType::class, ['required' => true])
            ->add(
                'submit',
                SubmitType::class,
                ['label' => 'reset_password.request_mail', 'translation_domain' => 'ddr_security']
            )
            ->getForm();

        $mailSent = false;
        $requestForm->handleRequest($request);
        if ($requestForm->isSubmitted() && $requestForm->isValid()) {
            $email = Asserted::string($requestForm->get('email')->getData());
            try {
                $this->resetPasswordService->requestPasswordReset($email);
                $mailSent = true;
            } catch (TooManyResetPasswordRequestsException $e) {
                $this->addFlash(
                    'error',
                    sprintf(
                        'Das Zurücksetzen des Passworts wurde bereits angefragt, Sie können es am %s erneut anfordern.',
                        $e->expiry->format('d.m.Y H:i:s')
                    )
                );
            }
        }

        $resetForm = $this->createFormBuilder(options: [
            'translation_domain' => 'ddr_security'
        ])
            ->add('password', RepeatedType::class, [
                'required'           => true,
                'type'               => PasswordType::class,
                'invalid_message'    => 'repeated_field_mismatch',
                'first_options'      => ['label' => 'reset_password.password'],
                'second_options'     => ['label' => 'reset_password.password_repeat'],
                'constraints'        => [
                    new NotBlank(),
                    new Password()
                ]
            ])
            ->add(
                'submit',
                SubmitType::class,
                ['label' => 'reset_password.set_password']
            )
            ->getForm();

        $resetForm->handleRequest($request);
        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            $password = Asserted::string($resetForm->get('password')->getData());
            $this->resetPasswordService->changePassword(Asserted::notNull($user), $password);
            $this->addFlash('success', $this->translator->trans(id: 'reset_password.success', domain: 'ddr_security'));

            return $this->redirectToRoute('ddr.bridge.security.login');
        }

        return $this->render('@DdrBridge/Security/reset_password.html.twig', [
            'requestForm' => $requestForm->createView(),
            'resetForm'   => $resetForm->createView(),
            'user'        => $user,
            'mailSent'    => $mailSent,
        ]);
    }
}
