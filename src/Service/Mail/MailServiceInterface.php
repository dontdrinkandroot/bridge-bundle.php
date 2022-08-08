<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Mail;

use Symfony\Component\Mime\Address;

interface MailServiceInterface
{
    /**
     * @param list<Address> $to
     * @param string        $subject
     * @param string        $markdown
     * @param list<Address> $cc
     * @param list<Address> $bcc
     *
     * @return void
     */
    public function sendMailMarkdown(
        array $to,
        string $subject,
        string $markdown,
        array $cc = [],
        array $bcc = []
    ): void;

    /**
     * @param list<Address> $to
     * @param string        $subject
     * @param string        $template
     * @param array         $templateParameters
     * @param list<Address> $cc
     * @param list<Address> $bcc
     *
     * @return void
     */
    public function sendMailMarkdownTemplate(
        array $to,
        string $subject,
        string $template,
        array $templateParameters = [],
        array $cc = [],
        array $bcc = []
    ): void;
}
