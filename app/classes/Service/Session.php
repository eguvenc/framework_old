<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\ServiceProvider\ServiceInterface,
    Obullo\Session\Session as SessionClass;

/**
 * Session Service
 *
 * @category  Service
 * @package   Session
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class Session implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(Container $c)
    {
        $c['session'] = function () use ($c) {
            return new SessionClass($c);
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Queue.php */