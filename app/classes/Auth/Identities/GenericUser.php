<?php

namespace Auth\Identities;

use Obullo\Authentication\AbstractUserIdentity;
use Obullo\Authentication\Identities\GenericUserInterface;

Class GenericUser extends AbstractUserIdentity implements GenericUserInterface
{
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        $id = $this->getColumnIdentifier();

        return $this->$id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getPassword()
    {
        $password = $this->getColumnPassword();

        return $this->$password;
    }

}

/* End of file GenericUser.php */
/* Location: .app/classes/Auth/Identities/GenericUser.php */