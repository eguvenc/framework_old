<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Service\ServiceInterface;

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
            $mailer = $c['app']->provider('mailer')->get(['driver' => 'smtp', 'options' => array('queue' => false)]);
            $mailer->from('Admin <admin@example.com>');
            return $mailer;
        };
    }
}

// END Mailer service

/* End of file Mailer.php */
/* Location: .app/classes/Service/Mailer.php */