<?php

namespace Auth;

/**
 * Define Your Credentials Constants
 *
 * @category  Auth
 * @package   Auth
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/auth
 */
Class Credentials
{
    /**
     * Identifier column name
     */
    const IDENTIFIER = 'username';

    /**
     * Identifier password column name
     */
    const PASSWORD = 'password';

     /**
     * None authorized user
     */
    const GUEST = 'Guest';

    /**
     * Login success but verification is not completed ( if verification enabled ).
     */
    const UNVERIFIED = 'Unverified';

    /**
     * Successfully authorized user
     */
    const AUTHORIZED = 'Authorized';
}


/* End of file Credentials.php */
/* Location: .app/classes/Auth/Credentials.php */