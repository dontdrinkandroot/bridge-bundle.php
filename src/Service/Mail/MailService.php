<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Mail;

use League\CommonMark\GithubFlavoredMarkdownConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService implements MailServiceInterface
{
    private GithubFlavoredMarkdownConverter $commonmarkConverter;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly Address $addressFrom,
        private readonly Address $addressReplyTo
    ) {
        $this->commonmarkConverter = new GithubFlavoredMarkdownConverter([
            'html_input'         => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMailMarkdown(
        array $to,
        string $subject,
        string $markdown,
        array $cc = [],
        array $bcc = []
    ): void {
        $message = new Email();
        $message->from($this->addressFrom);
        $message->replyTo($this->addressReplyTo);
        $message->to(...$to);
        $message->subject($subject);
        if (count($cc) > 0) {
            $message->cc(...$cc);
        }
        if (count($bcc) > 0) {
            $message->bcc(...$bcc);
        }
        $message->text($markdown);
        $message->html($this->commonmarkConverter->convert($markdown)->getContent());

        $this->mailer->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMailMarkdownTemplate(
        array $to,
        string $subject,
        string $template,
        array $templateParameters = [],
        array $cc = [],
        array $bcc = []
    ): void {
        $markdown = $this->twig->render($template, $templateParameters);
        $this->sendMailMarkdown($to, $subject, $markdown, $cc, $bcc);
    }
}
