<?php

namespace Service;

use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Mailer implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     *
     * Drivers : mandrill, smtp, sendmail, mail ..
     * 
     * @return void
     */
    public function register(ContainerInterface $c)
    {
        $c['mailer'] = function () use ($c) {

            return new MailerClass($c['app']->provider('mailer'));

            // $mailer = $c['app']->provider('mailer')->get(['driver' => 'smtp', 'options' => array('queue' => false)]);
            // $mailer->from('Admin <admin@example.com>');
            /// return $mailer;
        };
    }
}

// END Mailer service

/* End of file Mailer.php */
/* Location: .app/classes/Service/Mailer.php */