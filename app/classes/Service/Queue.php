<?php

namespace Service;

use Obullo\Queue\Handler\AMQP;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Queue implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(ContainerInterface $c)
    {
        $c['queue'] = function () use ($c) {
            return new AMQP($c);
        };
    }
}

// END Queue service

/* End of file Cache.php */
/* Location: .classes/queue.php */