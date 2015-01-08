<?php

namespace Service;

use Obullo\Mail\Connection;

/**
 * Mailer Provider
 *
 * @category  Service
 * @package   Mail
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class Mailer implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c        container
     * @param array  $commands loader command parameters ( new, return, as .. )
     * 
     * Providers : Mandrill, Smtp, Queue ..
     * 
     * @return void
     */
    public function register($c, $commands = array())
    {
        $c['provider:mailer'] = function ($params = array('provider' => 'mandrill', 'from' => '')) use ($c, $commands) {
            $connection = new Connection($c, $params, $commands);
            return $connection->connect();
        };
    }
}

// END Mailer class

/* End of file Mailer.php */
/* Location: .classes/Service/Mailer.php */