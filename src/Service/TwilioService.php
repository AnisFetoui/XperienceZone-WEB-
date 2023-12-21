<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid = 'AC43011fd5642c2860d02bc20c118f8a0c';
    private $authToken = '008a9a9dd77859fb9b1a19fc3caacc41';
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