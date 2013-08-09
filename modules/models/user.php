<?php
namespace Models;
use Ob;

Class User extends Ob\Model
{
    function __construct()
    {
        parent::__construct(new Schema\User());
    }
    
    function test()
    {
        echo 'Hello User !';
    }
}

/* End of file User.php */
/* Location: .modules/models/user.php */