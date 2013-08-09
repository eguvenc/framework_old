<?php

Class UserSchema 
{
    public $config = array(
    'database' => 'db',
    'table' => 'users',
    'primary_key' => '_id'
    );
    public $user_id = array(
    'label' => 'ID',
    'type' => 'int',
    'rules' => 'trim|integer'
    );
    public $user_password = array(
    'label' => 'ID',
    'type' => 'int',
    'rules' => 'trim|integer'
    );
}

Class User extends Odm
{
    function __construct($schema)
    {
        parent::__construct($schema);
    }
    
    /**
    * Update / Insert
    * 
    * @param mixed $val
    */
    function save()
    {   
        return parent::save();
    }

    /**
    * Do Validate and Delete
    *
    */
    function delete()
    {
        return parent::save();
    }
    
    // function before_save(){}
    // function after_save(){}
}


// $userSchema = new UserSchema();
// $userSchema->user_id['rules'] = 'trim|integer';  // Change default user_id validation rules.

$user = new User();
$user->schema->user_id['rules'] = 'trim|integer';
$user->save();

/* End of file User.php */
/* Location: .modules/welcome/models/user.php */
