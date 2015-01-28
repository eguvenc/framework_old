<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\ServiceProvider\ServiceInterface,
    Obullo\Authentication\UserServiceProvider;
    
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
        // $c['user.db'] = function () use ($c) {
        
        return $c->load('service provider pdo', array('connection' => 'default'));

        // return $c->load(
        //         'new service provider pdo', 
        //         array(
        //             'pdo.dsn' => 'pdo_mysql:dbname=test;host=127.0.0.1',
        //             'pdo.username' => 'username', // optional
        //             'pdo.password' => 'password', // optional
        //             'pdo.options' => array( // optional
        //                 PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        //             )
        //         )
        //     );
        // };

        $c['user'] = function () use ($c) {
            $user = new UserServiceProvider(
                $c,
                array(
                    'db.provider' => 'pdo',
                    'db.connection' => 'db',
                    'db.table' => 'users'
                )
            );
            return $user;
        };
    }
}

// END User class

/* End of file User.php */
/* Location: .app/classes/Service/User.php */