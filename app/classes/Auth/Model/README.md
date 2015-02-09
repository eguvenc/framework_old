
## Extending to Auth Model

------

You can create your own database model in here. Please read this documentation http://obullo.com/doc/2.0/authentication.

Below the example shows an user model class.

```php
<?php

namespace Auth\Model;

use Obullo\Authentication\Model\UserInterface,
    Obullo\Authentication\Model\User as ModelUser,
    Obullo\Container\Container,
    Auth\Identities\GenericUser,
    Auth\Identities\AuthorizedUser;

Class User extends ModelUser implements UserInterface
{
    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        parent::__construct($c);
    }
    
    /**
     * Execute sql query
     *
     * @param object $user GenericUser object to get user's identifier
     * 
     * @return mixed boolean|object
     */
    public function execQuery(GenericUser $user)
    {
        return parent::execQuery($user);
    }

}

// END User.php File
/* End of file User.php

/* Location: .app/classes/Auth/Model/User.php */
```