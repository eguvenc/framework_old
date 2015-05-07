<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Crypt\Password\Bcrypt;
use Obullo\Service\ServiceInterface;

class Password implements ServiceInterface
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