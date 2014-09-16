<?php

namespace Service\User;

use Service\ServiceInterface,
    Obullo\Auth\IdentityService;

/**
 * Identity Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Identity implements ServiceInterface
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
        $c['user/identity'] = function () use ($c) {
            return $c->load('return auth/user')->registerService(new IdentityService($c));
        };
    }
}

// END Identity class

/* End of file Identity.php */
/* Location: .classes/Service/User/Identity.php */