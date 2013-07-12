<?php
namespace Model\Welcome;
use Ob;

Class User extends Ob\Odm
{
    function __construct()
    {
        parent::__construct(new UserSchema);
    }
}

/* End of file User.php */
/* Location: .modules/welcome/models/user.php */