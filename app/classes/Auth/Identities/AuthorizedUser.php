<?php

namespace Auth\Identities;

use Obullo\Authentication\AbstractUserIdentity;
use Obullo\Authentication\Identities\AuthorizedUserInterface;

Class AuthorizedUser extends AbstractUserIdentity implements AuthorizedUserInterface
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

/* End of file AuthorizedUser.php */
/* Location: .app/classes/Auth/Identities/AuthorizedUser.php */