<?php

namespace Service;

use Obullo\Mail\Send\Protocol\Smtp;

/**
 * Mailer Service
 *
 * @category  Service
 * @package   Mail
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Mailer implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['mailer'] = function () use ($c) {
            return new Smtp($c, $c->load('config')['mail']);
        };
    }
}

// END Mailer class

/* End of file Mailer.php */
/* Location: .classes/Service/Mailer.php */