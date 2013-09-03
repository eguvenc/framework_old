<?php
namespace Model;

Class User extends \Odm {
    
    function __construct()
    {
        parent::__construct(new Schema\User());
    }
}

/*
$user = new Model/User();
$user->schema->user_id['rules'] = 'trim|integer';
$user->save();
*/

/* End of file user.php */
/* Location: .mods/tutorials/model/user.php */