<?php

namespace Auth\Identities;

use Obullo\Auth\Identities\IdentityInterface,
    Auth\Credentials;

/**
 * Generic Identity
 *
 * @category  Users
 * @package   Identities
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/auth
 */
Class GenericIdentity implements IdentityInterface
{
    /**
     * All of the user's attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Create a new generic User object.
     *
     * @param array $attributes identity array
     * 
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return isset($this->attributes[Credentials::IDENTIFIER]) ? $this->attributes[Credentials::IDENTIFIER] : false;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return isset($this->attributes[Credentials::PASSWORD]) ? $this->attributes[Credentials::PASSWORD] : false;
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
    
    /**
     * Dynamically access the user's attributes.
     *
     * @param string $key ket
     * 
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Dynamically set the user's attributes.
     *
     * @param string $key key
     * @param string $val value
     * 
     * @return mixed
     */
    public function __set($key, $val)
    {
        return $this->attributes[$key] = $val;
    }

    /**
     * Dynamically check if a value is set on the user.
     *
     * @param string $key key
     * 
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Dynamically unset a value on the user.
     *
     * @param string $key key
     * 
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

}

/* End of file GenericIdentity.php */
/* Location: .app/classes/Auth/Identities/GenericIdentity.php */