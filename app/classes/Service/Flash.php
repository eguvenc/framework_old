<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;
use Obullo\Flash\Session;

/**
 * Flash Service
 *
 * @category  Service
 * @package   Session
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
class Flash implements ServiceInterface
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
        $c['flash'] = function () use ($c) {
            return new Session($c);
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Queue.php */