<?php

namespace Auth\Identities;

use Obullo\Container\Container,
    Obullo\Authentication\AbstractGenericUser,
    Obullo\Authentication\Identities\GenericUserInterface;

/**
 * Generic User Identity
 *
 * @category  Users
 * @package   Identities
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/auth
 */
Class GenericUser extends AbstractGenericUser implements GenericUserInterface
{
    /**
     * Create a new generic User object.
     *
     * @param array $c          container
     * @param array $attributes user identities
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
    
    /**
     * Returns to "1" user if used remember me
     * 
     * @return integer
     */
    public function getRememberMe() 
    {
        return isset($this->attributes['__rememberMe']) ? $this->attributes['__rememberMe'] : 0;
    }

    /**
     * Returns to remember token
     * 
     * @return integer
     */
    public function getRememberToken() 
    {
        return isset($this->attributes['__rememberToken']) ? $this->attributes['__rememberToken'] : false;
    }
    
    /**
     * Get all attributes
     * 
     * @return array
     */
    public function getArray()
    {
        return $this->attributes;
    }
}

/* End of file GenericUser.php */
/* Location: .app/classes/Auth/Identities/GenericUser.php */