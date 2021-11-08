<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactMailController extends AbstractController
{
    #[Route('/api/mail', name:"mail", methods:["POST"])]
    public function mail(Request $request, MailerInterface $mailer)
    {
        $email = json_decode($request->getContent(),true)['email'];
        $subject = json_decode($request->getContent(),true)['subject'];
        $text = json_decode($request->getContent(),true)['text'];
        $email=(new Email())
                ->from($email)
                ->to('example@example.ex')
                ->subject($subject)
                ->text($text);
        $mailer->send($email);
        return $this->json($email,200);
    }
}