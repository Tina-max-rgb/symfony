<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendEmailService
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $template, array $context): void
    {
        $email = (new TemplatedEmail())
            ->from('bounoua.thinhinane@gmail.com')
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new BadRequestException('Une erreur s\'est produite lors de l\'envoi de l\'email');
        }
    }
}