<?php

/**
 * Obullo ODM
 */
Class UserSchema {
    function __construct(){
        $this->config['database'] = 'db';
        $this->config['table'] = 'users';
        $this->config['primary_key'] = '_id';
        
        $this->user_id = array(
        'label' => 'ID',
        'type' => 'int',
        'rules' => 'trim|integer'
        );
        $this->user_password = array(
        'label' => 'ID',
        'type' => 'int',
        'rules' => 'trim|integer'
        );
    }
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