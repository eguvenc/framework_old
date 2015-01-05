<?php

namespace Auth;

/**
 * Define Your Credentials Constants
 *
 * @category  Auth
 * @package   Credentials
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/auth
 */
Class Constant
{
    /**
     * Database tablename
     */
    const TABLENAME = 'users';

    /**
     * Id column name ( Primary key )
     */
    const ID = 'id';

    /**
     * Identifier column name
     */
    const IDENTIFIER = 'username';

    /**
     * Identifier password column name
     */
    const PASSWORD = 'password';

    /**
     * Remember me token column name
     */
    const REMEMBER_TOKEN = 'remember_token';
}


/* End of file Credentials.php */
/* Location: .app/classes/Auth/Credentials.php */