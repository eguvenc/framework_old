<?php

namespace Auth\Identities;

use Obullo\Container\Container,
    Obullo\Authentication\AbstractAuthorizedUser,
    Obullo\Authentication\Identities\AuthorizedUserInterface;

/**
 * User Identity
 *
 * @category  Auth
 * @package   Identities
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/auth
 */
Class AuthorizedUser extends AbstractAuthorizedUser implements AuthorizedUserInterface
{
    /**
     * Create a authorized user object.
     *
     * @param array $c          container
     * @param array $attributes identity data
     * 
     * @return void
     */
    public function __construct(Container $c, $attributes)
    {
        parent::__construct($c, $attributes);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return isset($this->attributes[$this->getColumnIdentifier()]) ? $this->attributes[$this->getColumnIdentifier()] : false;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return isset($this->attributes[$this->getColumnPassword()]) ? $this->attributes[$this->getColumnPassword()] : false;
    }
}

/* End of file AuthorizedUser.php */
/* Location: .app/classes/Auth/Identities/AuthorizedUser.php */