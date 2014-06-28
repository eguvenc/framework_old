<?php

namespace Service;

use Obullo\Auth\Auth as ObulloAuth;

/**
 * Auth Service
 *
 * @category  Auth
 * @package   Auth
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/container
 */
Class Auth implements ServiceInterface
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
        $c['auth'] = function () use ($c) {
            return new ObulloAuth(
                $c,
                array(
                    'sessionPrefix' => 'auth_'
                )
            );
        };
    }
}

// END Auth class

/* End of file Auth.php */
/* Location: .classes/Service/Auth.php */