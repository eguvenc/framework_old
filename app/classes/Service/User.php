<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;
use Obullo\Authentication\AuthServiceProvider;

class User implements ServiceInterface
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
        $c['user'] = function () use ($c) {

            $user = new AuthServiceProvider(
                $c,
                [
                    'cache.key'        => 'Auth:Site',
                    'db.adapter'       => '\Obullo\Authentication\Adapter\Database', // Adapter
                    'db.model'         => '\Obullo\Authentication\Model\User', // User model, you can replace it with your own.
                    'db.provider'      => 'database',
                    'db.connection'    => 'default',
                    'db.tablename'     => 'users', // Database column settings
                    'db.id'            => 'user_id',
                    'db.identifier'    => 'user_username',
                    'db.password'      => 'user_password',
                    'db.rememberToken' => 'remember_token',
                ]
            );

            return $user;
        };
    }
}

// END Authentication service

/* End of file User.php */
/* Location: .app/classes/Service/User.php */