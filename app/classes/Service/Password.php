<?php

namespace Service;

use Obullo\Crypt\Password\Bcrypt;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Password implements ServiceInterface
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
        $c['password'] = function () use ($c) {
            $bcrypt = new Bcrypt($c);
            $bcrypt->setIdentifier('2y');
            return $bcrypt;
        };
    }
}

// END Password service

/* End of file Password.php */
/* Location: .app/classes/Service/Password.php */