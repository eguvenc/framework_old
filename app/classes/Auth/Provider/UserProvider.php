<?php

namespace Auth\Provider;

use Obullo\Auth\UserProviderInterface,
    Obullo\Auth\AuthUserProvider,
    Auth\Identities\GenericUser,
    Auth\Identities\AuthorizedUser,
    Auth\Credentials;

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
Class UserProvider extends AuthUserProvider implements UserProviderInterface
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

        $this->tablename = 'users';                                // Db users tablename
        $this->rememberTokenColumn = Credentials::REMEMBER_TOKEN;  // RememberMe token column name

        $this->userSQL = 'SELECT * FROM %s WHERE BINARY %s = ?';      // Login attempt SQL
        $this->recalledUserSQL = 'SELECT * FROM %s WHERE %s = ?';     // Recalled user for remember me SQL
        $this->rememberTokenUpdateSQL = 'UPDATE %s SET %s = ? WHERE %s = ?';  // RememberMe token update SQL
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
        // return parent::execQuery($user);
        $this->db->prepare($this->userSQL, array($this->tablename, Credentials::IDENTIFIER));
        $this->db->bindValue(1, $user->getIdentifier(), PARAM_STR);
        $this->db->execute();

        return $this->db->rowArray();  // returns to false if fail
    }

}

// END UserProvider.php File
/* End of file UserProvider.php

/* Location: .app/classes/Auth/Provider/UserProvider.php */