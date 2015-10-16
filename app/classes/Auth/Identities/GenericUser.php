<?php

namespace Auth\Identities;

use Obullo\Authentication\Identity\AbstractIdentity;

class GenericUser extends AbstractIdentity
{
    /**
     * Get the identifier input value
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        $id = $this->getColumnIdentifier();

        return $this->$id;
    }

    /**
     * Get the password input value
     *
     * @return mixed
     */
    public function getPassword()
    {
        $password = $this->getColumnPassword();

        return $this->$password;
    }

}