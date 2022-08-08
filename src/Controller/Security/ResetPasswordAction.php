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
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordAction extends AbstractController
{
    public function __invoke(Request $request, ResetPasswordService $resetPasswordService): Response
    {
        $user = null;
        try {
            $user = $resetPasswordService->parse($request);
        } catch (InvalidResetPasswordTokenException $e) {
            $this->addFlash('error', 'Der Token zum Zurücksetzen des Passworts ist ungültig oder abgelaufen.');
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
                $resetPasswordService->requestPasswordReset($email);
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

        $resetForm = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Die Passwörter müssen übereinstimmen',
                'first_options' => ['label' => 'Passwort'],
                'second_options' => ['label' => 'Wiederholung'],
                'constraints' => [
                    new NotBlank(),
                    new Password()
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Passwort setzen'])
            ->getForm();

        $resetForm->handleRequest($request);
        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            $password = Asserted::string($resetForm->get('password')->getData());
            $resetPasswordService->changePassword(Asserted::notNull($user), $password);
            $this->addFlash('info', 'Das Passwort wurde erfolgreich zurückgesetzt.');

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
