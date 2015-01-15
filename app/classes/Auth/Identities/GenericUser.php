<?php

namespace Auth\Identities;

use Obullo\Authentication\Identities\GenericUserInterface;

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
Class GenericUser implements GenericUserInterface
{
    protected $attributes;
    protected $identifier;
    protected $password;

    /**
     * Create a new generic User object.
     *
     * @param array $params     auth table parameters
     * @param array $attributes user identities
     * 
     * @return void
     */
    public function __construct(array $params, array $attributes)
    {
        $this->attributes = $attributes;
        $this->identifier = $params['identifier'];
        $this->password   = $params['password'];
    }
    
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return isset($this->attributes[$this->identifier]) ? $this->attributes[$this->identifier] : false;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return isset($this->attributes[$this->password]) ? $this->attributes[$this->password] : false;
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

/* End of file GenericUser.php */
/* Location: .app/classes/Auth/Identities/GenericUser.php */