<?php

namespace Auth\Identities;

use Obullo\Auth\Identities\IdentityInterface;

/**
 * User Identity
 *
 * @category  Auth
 * @package   Identities
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/auth
 */
Class User implements IdentityInterface
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
        return $this->attributes['id'];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->attributes['password'];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getIsVerified()
    {
        return isset($this->attributes['__isVerified']) ? $this->attributes['__isVerified'] : 0;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getIsAuthenticated()
    {
        return $this->attributes['__isAuthenticated'];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getIsTempAuth()
    {
        return $this->attributes['__isTempAuth'];
    }

    /**
     * Get user type : UNVERIFIED, AUTHORIZED, GUEST
     * 
     * @return string
     */
    public function getType()
    {
        return $this->attributes['__type'];
    }

    /**
     * Returns to "1" user if used remember me
     * 
     * @return integer
     */
    public function getRememberMe() 
    {
        return $this->attributes['__rememberMe'];
    }

    /**
     * Get roles of user
     * 
     * @return mixed string|array
     */
    public function getRoles()
    {
        return isset($this->attributes['__roles']) ? unserialize($this->attributes['__roles']) : '';
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
     * Set an attribute on the user.
     *
     * @param string $key   key
     * @param mixed  $value value
     * 
     * @return void
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get an attribute on the user.
     *
     * @param string $key key
     * 
     * @return void
     */
    public function get($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Unset a value on the user.
     *
     * @param string $key key
     * 
     * @return bool
     */
    public function remove($key)
    {
        unset($this->attributes[$key]);
    }

}