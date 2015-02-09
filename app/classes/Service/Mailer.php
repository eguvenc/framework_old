<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;
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
class Mailer implements ServiceInterface
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
    public function register(Container $c)
    {
        $c['mailer'] = function () use ($c) {
            $mailer =  $c['service provider mailer']->get(
                [
                    'driver' => 'mandrill',
                ]
            );
            $mailer->from($c['config']['mail']['send']['from']['address']);
            return $mailer;

            // $mailer = new Queue($c);
            // $mailer->from($c['config']['mail']['send']['from']['address']);
            // return $mailer;
        };
    }
}

// END Mailer class

/* End of file Mailer.php */
/* Location: .classes/Service/Mailer.php */