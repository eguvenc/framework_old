<?php

namespace Auth\Login;

use Obullo\Auth\Adapter\AssociativeArray,
    Obullo\Auth\LoginQueryInterface,
    Auth\Identities\GenericIdentity;

/**
 * O2 Auth User Query 
 *
 * @category  Auth
 * @package   Auth
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/auth
 */
Class Query implements LoginQueryInterface
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
     * Sql expression
     */
    const SQL_EXPRESSION = 'SELECT * FROM users WHERE BINARY username = ?';

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
        $this->db = $this->c->load('return db');
    }

    /**
     * Execute sql query
     *
     * @param array $user GenericIdentity object to get user's identifier
     * 
     * @return mixed boolean|array
     */
    public function execDatabase(GenericIdentity $user)
    {
        $this->db->prepare(static::SQL_EXPRESSION);
        $this->db->bindValue(1, $user->getIdentifier(), PARAM_STR);
        $this->db->execute();

        return $this->db->rowArray();  // returns to false if fail
    }

    /**
     * Execute storage query
     *
     * @return mixed boolean|array
     */
    public function execStorage()
    {
        return $this->storage->query();
    }

}

// END Query.php File
/* End of file Query.php

/* Location: .app/classes/Auth/Login/Query.php */