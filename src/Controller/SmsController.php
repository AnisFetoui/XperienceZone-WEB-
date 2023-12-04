<?php
namespace App\Controller;

use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    #[Route('/send-sms', name: 'send_sms')]
    public function sendSms(TwilioService $twilioService): Response
    {
        $to = '+21692103963'; // Le numéro de téléphone destinataire
        $message = 'Votre message ici'; // Le message que vous souhaitez envoyer

        $twilioService->sendSMS($to, $message);

        return new Response('SMS envoyé !');
    }}