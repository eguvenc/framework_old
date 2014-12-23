<?php

namespace Service;

use Obullo\Mail\Transport\Queue;

/**
 * Mailer Service
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
     * @param object $c container
     *
     * Providers : Mandrill, Smtp, Queue ..
     * 
     * @return void
     */
    public function register($c)
    {
        $c['mailer'] = function () use ($c) {
            $mailer = new Queue($c, $c['config']['mail']);
            $mailer->from($c['config']['mail']['send']['from']['address']);
            return $mailer;
        };
    }
}

// END Mailer class

/* End of file Mailer.php */
/* Location: .classes/Service/Mailer.php */