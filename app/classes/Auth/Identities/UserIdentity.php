<?php

namespace Auth\Identities;

use Obullo\Auth\Identities\IdentityInterface,
    Auth\Credentials;

/**
 * User Identity
 *
 * @category  Auth
 * @package   Identities
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
 * @link      http://obullo.com/package/auth
 */
Class UserIdentity implements IdentityInterface
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
     * @param array $attributes user identities
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
        return isset($this->attributes[Credentials::PASSWORD]) ? $this->attributes[Credentials::IDENTIFIER] : false;
    }
    
    /**
     * Dynamically access the user's attributes.
     *
     * @param string $key key
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

/* End of file UserIdentity.php */
/* Location: .app/classes/Auth/Identities/UserIdentity.php */