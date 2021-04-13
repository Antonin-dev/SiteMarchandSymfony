<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '';
    private $api_key_secret = '';

    public function send($to_email, $to_name, $subject, $content)
    {
    $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "antonin.val@gmail.com",
                    'Name' => "La Boutique Francaise"
                ],
                'To' => [
                    [
                        'Email' => $to_email,
                        'Name' => $to_name
                    ]
                ],
                'TemplateID' => 2672200,
                'TemplateLanguage' => true,
                'Subject' => $subject,
                'Variables' => [
                    'content' => $content,
                    
                ]
            ]
        ]
    ];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success();
    }
}