<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;
use Obullo\Crypt\Password\Bcrypt;

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
            return new Bcrypt($c);
        };
    }
}

// END Password service

/* End of file Password.php */
/* Location: .classes/Service/Password.php */