<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid = 'AC43011fd5642c2860d02bc20c118f8a0c';
    private $authToken = '158999d8ea986e539d9a724fe80f5267';
    private $twilioPhoneNumber = '+12672974905';
   
    public function sendSMS($to, $body)
    {
        $client = new Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $body,
            ]
        );
    }
}