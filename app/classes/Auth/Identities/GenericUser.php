<?php

namespace Auth\Identities;

use Obullo\Container\Container,
    Obullo\Authentication\AbstractUserIdentity,
    Obullo\Authentication\Identities\GenericUserInterface;

Class GenericUser extends AbstractUserIdentity implements GenericUserInterface
{
    /**
     * Create a new generic User object.
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

/* End of file GenericUser.php */
/* Location: .app/classes/Auth/Identities/GenericUser.php */