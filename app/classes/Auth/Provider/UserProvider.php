<?php

namespace Auth\Provider;

use Obullo\Auth\UserProviderInterface,
    Obullo\Auth\UserProvider as ObulloUserProvider,
    Auth\Identities\GenericUser,
    Auth\Identities\AuthorizedUser,
    Auth\Constant;

/**
 * O2 Auth - User Database Provider
 *
 * @category  Auth
 * @package   Provider
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/auth
 */
Class UserProvider extends ObulloUserProvider implements UserProviderInterface
{
    /**
     * Constructor
     * 
     * @param object $c  container
     * @param object $db database
     */
    public function __construct($c, $db)
    {
        parent::__construct($c, $db);
    }
    
    /**
     * Execute sql query
     *
     * @param object $user GenericUser object to get user's identifier
     * 
     * @return mixed boolean|object
     */
    public function execQuery(GenericUser $user)
    {
        return parent::execQuery($user);
    }

}

// END UserProvider.php File
/* End of file UserProvider.php

/* Location: .app/classes/Auth/Provider/UserProvider.php */