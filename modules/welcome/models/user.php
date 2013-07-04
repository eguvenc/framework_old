<?php
  
Class User extends Odm
{
    function __construct()
    {
        parent::__construct();
    }
    
    public $settings = array(
    'database' => 'db',
    'table'    => 'users',
    'primary_key' => 'user_id',
    'fields' => array
     (
        'user_id' => array(
          'label' => 'ID',
          'type'  => 'int',
          'rules' => 'trim|integer'
        ),
        'user_password' => array(
          'label' => 'Password',
          'type'  => 'string',
          'rules' => 'required|trim|min_len[6]|encrypt',
          'func'  => 'md5'
        ),
        'user_confirm_password' => array(
          'label' => 'Confirm Password',
          'type'  => 'string',
          'rules' => 'required|encrypt|matches[user_password]'
        ),
        'user_email' => array(
          'label' => 'Email Address',
          'type'  => 'string',
          'rules' => 'required|trim|valid_email'
        )
        
    ));
    
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