<?php
namespace Models;

Class User extends \Odm {
    
    function __construct()
    {
        parent::__construct(new Schema\User());
    }
}

/*
$user = new Models/User();
$user->schema->user_id['rules'] = 'trim|integer';
$user->save();
*/

/* End of file user.php */
/* Location: .modules/models/user.php */