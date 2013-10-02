<?php
namespace Schema;

Class User
{
    /* Table Configuration */
    public $config        = array('database' => 'db', 'table' => 'users', 'primary_key' => 'user_id');
    /* Table Fields */
    public $user_id       = array('label' => 'ID', 'type' => 'int', 'rules' => 'trim|integer');
    public $user_password = array('label' => 'Password', 'type' => 'string', 'rules' => 'required|trim|minLen[6]', 'func' => 'md5');
    public $user_confirm_password = array('label' => 'Confirm Password', 'type' => 'string', 'rules' => 'required|matches[user_password]');
    public $user_email    = array('label' => 'Email Address', 'type' => 'string', 'rules' => 'required|trim|validEmail');
}

/* End of file user.php */
/* Location: .modules/models/schema/user.php */