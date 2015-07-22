<?php

namespace Service;

use Obullo\Mail\MailManager;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Mailer implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(ContainerInterface $c)
    {
        $c['mailer'] = function () use ($c) {

            $parameters = [
                'queue' => [
                    'channel' => 'mail',
                    'route' => 'mailer.1',
                    'delay' => 0,
                ],
                'provider' => [
                    'mandrill' => [
                        'url' => 'https://mandrillapp.com/api/1.0/messages/send.json',
                        'key' => 'BIK8O7xt1Kp7aZyyQ55uOQ',
                        'pool' => 'Main Pool',
                    ],
                    'mailgun' => [
                        'url' => 'https://api.mailgun.net/v2/samples.mailgun.org/messages',
                        'key' => ''
                    ]
                ]
            ];
            $manager = new MailManager($c);
            $manager->setConfiguration($parameters);

            $mailer = $manager->getMailer('mandrill');
            $mailer->from('Admin <admin@example.com>');
            return $mailer;
        };
    }
}