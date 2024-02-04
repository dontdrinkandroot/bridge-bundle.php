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

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage(0);
        self::assertNotNull($email);

        $this->assertEmailSubjectContains( $email,'Test Subject');
        $this->assertEmailHtmlBodyContains($email, 'Test <em>Markdown</em>');
    }
}
