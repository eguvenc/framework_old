<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\ServiceProvider\ServiceInterface,
    Obullo\Authentication\AuthServiceProvider;
    
/**
 * User Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class User implements ServiceInterface
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
                    'cache.key'        => 'Auth',
                    'db.adapter'       => '\Obullo\Authentication\Adapter\Database', // Adapter
                    'db.model'         => '\Obullo\Authentication\Model\User', // User model, you can replace it with your own.
                    'db.provider'      => 'database',
                    'db.connection'    => 'default',
                    'db.tablename'     => 'users', // Database column settings
                    'db.id'            => 'id',
                    'db.identifier'    => 'email',
                    'db.password'      => 'password',
                    'db.rememberToken' => 'remember_token',
                ]
            );
            return $user;
        };
    }
}

// END User class

/* End of file User.php */
/* Location: .app/classes/Service/User.php */