<?php

namespace Service;

use Obullo\Authentication\UserService,
    Service\ServiceInterface;
    
/**
 * User Service ( Shared )
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
    public function register($c)
    {
        $c['user.db'] = function () use ($c) {
            return $c->load('service provider pdo', array('connection' => 'db', 'driver' => 'mysql'));

            return $c->load(
                'new service provider pdo', 
                array(
                    'pdo.dsn' => 'pdo_mysql:dbname=test;host=127.0.0.1',
                    'pdo.username' => 'username', // optional
                    'pdo.password' => 'password', // optional
                    'pdo.options' => array( // optional
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                    )
                )
            );
        };
        $c['user'] = function ($params = array('table' => 'users')) use ($c) {
            return new UserService($c, $params);
        };
    }
}

// END User class

/* End of file User.php */
/* Location: .app/classes/Service/User.php */