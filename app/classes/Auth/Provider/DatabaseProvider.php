<?php

namespace Auth\Provider;

use Obullo\Auth\Adapter\AssociativeArray,
    Obullo\Auth\DatabaseProviderInterface,
    Auth\Identities\GenericIdentity,
    Auth\Identities\UserIdentity;

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
Class DatabaseProvider implements DatabaseProviderInterface
{
    /**
     * Container
     * 
     * @var object
     */
    public $c;

    /**
     * Database
     * 
     * @var object
     */
    public $db;

    /**
     * Db users tablename
     */
    const TABLE = 'users';

    /**
     * Db identifier column name
     */
    const IDENTIFIER = 'username';

    /**
     * RememberMe token column name
     */
    const REMEMBER_TOKEN = 'remember_token';

    /**
     * Sql expression
     */
    const SQL_USER = 'SELECT * FROM %s WHERE BINARY %s = ?';

    /**
     * Sql expression of recalled user
     */
    const SQL_RECALLED_USER = 'SELECT * FROM %s WHERE %s = ?';

    /**
     * Sql expression of update remember token
     */
    const SQL_UPDATE_REMEMBER_TOKEN = 'UPDATE %s SET %s = ? WHERE BINARY %s = ?';

    /**
     * Constructor
     * 
     * @param object $c       container
     * @param object $storage memory storage
     */
    public function __construct($c, $storage)
    {
        $this->c = $c;
        $this->storage = $storage;
        $this->db = $this->c->load('return service/provider/db');
    }

    /**
     * Execute sql query
     *
     * @param object $user GenericIdentity object to get user's identifier
     * 
     * @return mixed boolean|array
     */
    public function execQuery(GenericIdentity $user)
    {
        $this->db->prepare(static::SQL_USER, array(static::TABLE, static::IDENTIFIER));
        $this->db->bindValue(1, $user->getIdentifier(), PARAM_STR);
        $this->db->execute();

        return $this->db->rowArray();  // returns to false if fail
    }

    /**
     * Recalled user sql query using remember cookie
     * 
     * @param string $token rememberMe token
     * 
     * @return array
     */
    public function execRecallerQuery($token)
    {
        $this->db->prepare(static::SQL_RECALLED_USER, array(static::TABLE, static::REMEMBER_TOKEN));
        $this->db->bindValue(1, $token, PARAM_STR);
        $this->db->execute();

        return $this->db->rowArray();  // returns to false if fail
    }

    /**
     * Update remember token upon every login & logout
     * 
     * @param string $token name
     * @param object $user  object UserIdentity
     * 
     * @return void
     */
    public function refreshRememberMeToken($token, GenericIdentity $user)
    {
        $this->db->prepare(static::SQL_UPDATE_REMEMBER_TOKEN, array(static::TABLE, static::REMEMBER_TOKEN, static::IDENTIFIER));
        $this->db->bindValue(1, $token, PARAM_STR);
        $this->db->bindValue(2, $user->getIdentifier(), PARAM_STR);
        $this->db->execute();
    }

}

// END DatabaseProvider.php File
/* End of file DatabaseProvider.php

/* Location: .app/classes/Auth/Provider/DatabaseProvider.php */