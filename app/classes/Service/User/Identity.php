<?php

namespace Service\User;

use Obullo\Auth\IdentityService,
    Obullo\Auth\Temporary\Storage\Cache;

/**
 * User ( Authentication ) Login Service
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

            $user = $c->load('return auth/user');
            $user->registerService(new LoginService($c, new DbTable, new Cache));
            return $user;
        };
    }
}

// END User class

/* End of file User.php */
/* Location: .classes/Service/User.php */