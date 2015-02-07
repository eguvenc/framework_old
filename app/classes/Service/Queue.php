<?php

namespace Service;

use Obullo\Queue\Handler\AMQP;
use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

/**
 * Queue Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/providers
 */
class Queue implements ServiceInterface
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
        $c['queue'] = function () use ($c) {
            return new AMQP($c);
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/queue.php */