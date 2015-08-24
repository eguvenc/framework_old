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
                    'route' => 'mailer.1',
                    'delay' => 0,
                ],
                'provider' => [
                    'mandrill' => [
                        'pool' => 'Main Pool',
                        'key' => 'BIK8O7xt1Kp7aZyyQ55uOQ',
                        'class' => '\Obullo\Mail\Provider\Mandrill',
                        'async' => false,
                    ],
                    'mailgun' => [
                        'domain' => 'news.obullo.com',
                        'key' => 'key-1c520989a3e6c6917ccd14d1f59c5fde',
                        'class' => 'Obullo\Mail\Provider\Mailgun'
                    ]
                ]
            ];
            $mailer = new MailManager($c);
            $mailer->setParameters($parameters);
            $mailer->setProvider('mandrill');
            $mailer->from('Admin <admin@example.com>');
            return $mailer;
        };
    }
}