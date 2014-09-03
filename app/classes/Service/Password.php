<?php

namespace Service;

use Obullo\Crypt\Password\Bcrypt;

/**
 * Password Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Password implements ServiceInterface
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
        $c['password'] = function () use ($c) {
            return new Bcrypt($c);
        };
    }
}

// END Password class

/* End of file Password.php */
/* Location: .classes/Service/Password.php */