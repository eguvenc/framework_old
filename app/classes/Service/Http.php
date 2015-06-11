<?php

namespace Service;

use GuzzleHttp\Client;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Http implements ServiceInterface
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
        $c['http'] = function () {
            return new Client;
        };
    }
}

// END Http service

/* End of file Http.php */
/* Location: .app/classes/Service/Http.php */