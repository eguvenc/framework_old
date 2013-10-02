<?php
namespace Models;

Class User extends \Odm {
    
    function __construct()
    {
        parent::__construct(new Schema\User());
    }   
}

/* End of file user.php */
/* Location: .modules/models/user.php */