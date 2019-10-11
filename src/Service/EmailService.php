<?php

namespace App\Service;

class EmailService
{
    private $mailer;
    private $webmaster;

    public function __construct(\Swift_Mailer $mailer, string $webmaster)
    {
        $this->mailer = $mailer;
        $this->webmaster = $webmaster;
    }

    public function sendEmail($data)
    {
        $message = (new \Swift_Message('Contact Email'))
        ->setFrom([$data['email'] => $data['name']])
        ->setTo($this->webmaster) //automatiquement renvoyé au webmaster
        ->setSubject($data['subject'])
        ->setBody($data['message'],'text/plain')
        ;
        
        return $this->mailer->send($message);
    }
}