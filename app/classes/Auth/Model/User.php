<?php

namespace Auth\Model;

use Obullo\Authentication\Model\UserInterface,
    Obullo\Authentication\Model\User as ModelUser,
    Auth\Identities\GenericUser,
    Auth\Identities\AuthorizedUser,
    Auth\Constant;

/**
 * O2 Auth - User Database Model
 *
 * @category  Auth
 * @package   Provider
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/auth
 */
Class User extends ModelUser implements UserInterface
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

// END User.php File
/* End of file User.php

/* Location: .app/classes/Auth/Model/User.php */