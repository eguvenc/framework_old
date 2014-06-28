<?php

namespace Service;

use Obullo\Password\Bcrypt;

/**
 * Password Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/container
 */
Class Password implements ServiceInterface
{
    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct($c)
    {
        $c['password'] = function () {
            return new Bcrypt;
        };
    }
}

// END Password class

/* End of file Password.php */
/* Location: .classes/Service/Password.php */