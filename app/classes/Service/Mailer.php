<?php

return  array(
    
    'queue' => [
        'job' => 'mailer.1',
        'delay' => 0,
    ],
    'providers' => [
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
    ],
    'methodCall' => [
        'mailer.setProvider' => 'mandrill',
        'mailer.from' => 'Admin <admin@example.com>',
    ]
);

use Symfony\Component\DependencyInjection\Reference;

// ...
$c->setParameter('mailer.provider', 'mandrill');
$c
    ->register('mailer', 'Mailer')
    ->addArgument('%mailer.provider%');

$c
    ->register('newsletter_manager', 'NewsletterManager')
    ->addMethodCall('setMailer', array(new Reference('mailer')));

// namespace Service;

// use Obullo\Mail\MailManager;
// use Obullo\Container\ServiceInterface;
// use Obullo\Container\ContainerInterface;

// class Mailer implements ServiceInterface
// {
//     /**
//      * Registry
//      *
//      * @param object $c container
//      * 
//      * @return void
//      */
//     public function register(ContainerInterface $c)
//     {
//         $c['mailer'] = function () use ($c) {

//             $parameters = [
//                 'queue' => [
//                     'job' => 'mailer.1',
//                     'delay' => 0,
//                 ],
//                 'providers' => [
//                     'mandrill' => [
//                         'pool' => 'Main Pool',
//                         'key' => 'BIK8O7xt1Kp7aZyyQ55uOQ',
//                         'class' => '\Obullo\Mail\Provider\Mandrill',
//                         'async' => false,
//                     ],
//                     'mailgun' => [
//                         'domain' => 'news.obullo.com',
//                         'key' => 'key-1c520989a3e6c6917ccd14d1f59c5fde',
//                         'class' => 'Obullo\Mail\Provider\Mailgun'
//                     ]
//                 ],
//                 'methods' => [
//                     'setProvider' => 'mandrill',
//                     'from' => 'Admin <admin@example.com>',
//                 ]
//             ];
//             $mailer = new MailManager($c);
//             $mailer->setParameters($parameters);
//             $mailer->setProvider('mandrill');
//             $mailer->from('Admin <admin@example.com>');
//             return $mailer;
//         };
//     }
// }