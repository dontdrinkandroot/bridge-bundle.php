<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Mail;

use Symfony\Component\Mime\Address;

interface MailServiceInterface
{
    /**
     * @param Address|Address[] $to
     * @param string $subject
     * @param string $markdown
     * @param Address[] $cc
     * @param Address[] $bcc
     *
     * @return void
     */
    public function sendMailMarkdown(
        Address|array $to,
        string $subject,
        string $markdown,
        array $cc = [],
        array $bcc = []
    ): void;

    /**
     * @param Address|Address[] $to
     * @param string $subject
     * @param string $template
     * @param array<string,mixed> $templateParameters
     * @param Address[] $cc
     * @param Address[] $bcc
     *
     * @return void
     */
    public function sendMailMarkdownTemplate(
        Address|array $to,
        string $subject,
        string $template,
        array $templateParameters = [],
        array $cc = [],
        array $bcc = []
    ): void;
}
