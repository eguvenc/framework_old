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
        $c['user'] = function () use ($c) {
            return new UserService($c, $c->load('return service/provider/db'));
        };
    }

}

// END User class

/* End of file User.php */
/* Location: .app/classes/Service/User.php */