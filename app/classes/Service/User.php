<?php

namespace Service;

use Obullo\Auth\UserService,
    Service\ServiceInterface;

/**
 * User Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
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
        $c['user'] = function () use ($c) {
            return new UserService($c);
        };
    }

}

// END User class

/* End of file User.php */
/* Location: .app/classes/Service/User.php */