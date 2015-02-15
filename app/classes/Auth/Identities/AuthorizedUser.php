<?php

namespace Auth\Identities;

use Obullo\Container\Container,
    Obullo\Authentication\AbstractUserIdentity,
    Obullo\Authentication\Identities\AuthorizedUserInterface;

Class AuthorizedUser extends AbstractUserIdentity implements AuthorizedUserInterface
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