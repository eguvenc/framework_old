<?php

namespace Auth\Identities;

use Obullo\Auth\Identities\IdentityInterface;

/**
 * Genetic Identity
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