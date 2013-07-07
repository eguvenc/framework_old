<?php
  
Class UserSchema 
{
    public $config = array(
    'database' => 'db',
    'table' => 'users',
    'primary_key' => 'user_id'
    );
    public $user_id = array(
    'label' => 'ID',
    'type' => 'int',
    'rules' => 'trim|integer'
    );
    public $user_password = array(
    'label' => 'Password',
    'type'  => 'string',
    'rules' => 'required|trim|min_len[6]|encrypt',
    'func'  => 'md5'
    );
    public $user_confirm_password = array(
    'label' => 'Confirm Password',
    'type'  => 'string',
    'rules' => 'required|encrypt|matches[user_password]'
    );
    public $user_email = array(
    'label' => 'Email Address',
    'type'  => 'string',
    'rules' => 'required|trim|valid_email'
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
        return parent::delete();
    }
}

/* End of file User.php */
/* Location: .modules/welcome/models/user.php */