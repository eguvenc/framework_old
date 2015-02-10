<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

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
     * Drivers : mandrill, smtp, queue ..
     * 
     * @return void
     */
    public function register(Container $c)
    {
        $c['mailer'] = function () use ($c) {
            $mailer = $c['service provider mailer']->get(
                [
                    'driver' => 'mandrill',
                    'options' => array('queue' => true)
                ]
            );
            $mailer->from('Admin <admin@example.com>');
            return $mailer;
        };
    }
}

// END Mailer class

/* End of file Mailer.php */
/* Location: .classes/Service/Mailer.php */