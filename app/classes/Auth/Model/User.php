<?php

namespace Auth\Model;

use Obullo\Auth\Adapter\AssociativeArray,
    Obullo\Auth\ModelUserInterface,
    Auth\Identities\GenericIdentity,
    Auth\Identities\UserIdentity;

/**
 * O2 Auth - User Database Model
 *
 * @category  Auth
 * @package   Auth
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/auth
 */
Class User implements ModelUserInterface
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
     * Execute storage query
     *
     * @return mixed boolean|array
     */
    public function execStorageQuery()
    {
        return $this->storage->query();
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
        $this->db->update(
            static::TABLE, 
            array(static::REMEMBER_TOKEN => $token), 
            array(static::IDENTIFIER => $user->getIdentifier())
        );
    }

}

// END User.php File
/* End of file User.php

/* Location: .app/classes/Auth/Model/User.php */