<?php

namespace Service\User;

use Service\ServiceInterface,
    Obullo\Auth\LoginService,
    Obullo\Auth\Adapter\DbTable,
    Auth\Adapter\DbTable\SQLQuery;

/**
 * Login Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Login implements ServiceInterface
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
        $c['user/login'] = function () use ($c) {
            return $c->load('return auth/user')->registerService(
                new LoginService(
                    $c, 
                    new DbTable(new SQLQuery($c))
                )
            );
        };
    }

}

// END Login class

/* End of file Login.php */
/* Location: .app/classes/Service/Login.php */