<?php

namespace Auth\Adapter\DbTable;

use Obullo\Auth\Adapter\DbTable,
    Obullo\Auth\Adapter\DbTable\QueryInterface,
    Auth\Identities\GenericIdentity;

/**
 * Query 
 *
 * @category  Auth
 * @package   Auth
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/auth
 */
Class SQLQuery implements QueryInterface
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
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->c = $c;
        $this->db = $this->c->load('return db');
    }

    /**
     * Execute sql query
     *
     * @param array $credentials identifier and password
     * 
     * @return object
     */
    public function exec(array $credentials)
    {
        $this->db->prepare(static::SQL_EXPRESSION);
        $this->db->bindValue(1, $credentials['id'], PARAM_STR);
        $this->db->execute();

        return new GenericIdentity($this->db->rowArray());
    }
}

// END SQLQuery.php File
/* End of file SQLQuery.php

/* Location: .app/classes/Auth/Adapter/DbTable/SQLQuery.php */