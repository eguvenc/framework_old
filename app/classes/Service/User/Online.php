<?php

namespace Service\User;

use Service\ServiceInterface,
    Obullo\Auth\OnlineService;

/**
 * Online Users Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Online implements ServiceInterface
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
        $c['user/online'] = function () use ($c) {
            return $c->load('return auth/user')->registerService(new OnlineService($c));
        };
    }
}

// END Online class

/* End of file Online.php */
/* Location: .classes/Service/User/Online.php */