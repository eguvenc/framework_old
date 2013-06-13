<?php

/**
 * Obullo ODM
 */
Class UserSchema {
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

$user_schema = new \UserSchema();
$user_schema->user_id['rules'] = 'trim|integer'; 

Class User extends ODM
{
    function __construct($schema = '')
    {
        parent::__construct($schema);
    }
    
    function before_save(){}
    function after_save(){}
    
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
}

/* End of file User.php */
/* Location: .modules/welcome/models/user.php */