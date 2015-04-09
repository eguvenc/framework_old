<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

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
    public function register(Container $c)
    {
        $c['mailer'] = function () use ($c) {

            $mailer = $c['service provider mailer']->get(['driver' => 'smtp', 'options' => array('queue' => false)]);
            $mailer->from('Admin <admin@example.com>');
            return $mailer;
        };
    }
}

// END Mailer service

/* End of file Mailer.php */
/* Location: .classes/Service/Mailer.php */