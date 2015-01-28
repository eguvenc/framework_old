<?php

namespace Service;

use Service\ServiceInterface,
    Obullo\Container\Container,
    Obullo\Authentication\UserServiceProvider,
    
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
            $user = new UserServiceProvider(
                $c,
                array(
                    'db.provider'   => 'pdo',
                    'db.connection' => 'db',
                    'db.table'      => 'users'
                )
            );
            return $user;
        };
    }
}

// END User class

/* End of file User.php */
/* Location: .app/classes/Service/User.php */