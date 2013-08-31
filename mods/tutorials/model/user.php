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

/* End of file User.php */
/* Location: .mods/model/user.php */