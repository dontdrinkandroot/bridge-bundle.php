<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\Integration\Service\Mail;

use Dontdrinkandroot\BridgeBundle\Service\Mail\MailServiceInterface;
use Dontdrinkandroot\BridgeBundle\Tests\WebTestCase;
use Symfony\Component\Mime\Address;

class MailServiceTest extends WebTestCase
{
    public function testSendMailMarkdown(): void
    {
        $mailService = self::getService(MailServiceInterface::class);
        $mailService->sendMailMarkdown(
            to: Address::create('test@example.com'),
            subject: 'Test Subject',
            markdown: 'Test *Markdown*'
        );

        self::assertEmailCount(1);

        $email = self::getMailerMessage(0);
        self::assertNotNull($email);

        self::assertEmailSubjectContains($email, 'Test Subject');
        self::assertEmailHtmlBodyContains($email, 'Test <em>Markdown</em>');
    }
}
