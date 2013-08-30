<?php
namespace Models\Schema;

Class User
{
    public $config        = array('database' => 'db', 'table' => 'users', 'primary_key' => 'user_id');
    public $user_id       = array('label' => 'ID', 'type' => 'int', 'rules' => 'trim|integer');
    public $user_password = array('label' => 'Password', 'type' => 'string', 'rules' => 'required|trim|minLen[6]|encrypt', 'func' => 'md5');
    public $user_confirm_password = array('label' => 'Confirm Password', 'type' => 'string', 'rules' => 'required|encrypt|matches[user_password]');
    public $user_email    = array('label' => 'Email Address', 'type' => 'string', 'rules' => 'required|trim|validEmail');
}