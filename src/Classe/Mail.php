<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '01441d65ab28009beab9bf7ec2f8618e';
    private $api_key_secret = 'b0dd509b12c3ca30a0b467545d1faa4d';

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