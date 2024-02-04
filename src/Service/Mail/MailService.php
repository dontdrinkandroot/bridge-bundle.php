<?php

namespace Dontdrinkandroot\BridgeBundle\Service\Mail;

use League\CommonMark\GithubFlavoredMarkdownConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService implements MailServiceInterface
{
    private readonly GithubFlavoredMarkdownConverter $commonmarkConverter;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly Address $addressFrom,
        private readonly ?Address $addressReplyTo
    ) {
        $this->commonmarkConverter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMailMarkdown(
        Address|array $to,
        string $subject,
        string $markdown,
        array $cc = [],
        array $bcc = []
    ): void {
        $actualTo = is_array($to) ? $to : [$to];

        $message = new Email();
        $message->from($this->addressFrom);
        if (null !== $this->addressReplyTo) {
            $message->replyTo($this->addressReplyTo);
        }
        $message->to(...$actualTo);
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
        Address|array $to,
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
