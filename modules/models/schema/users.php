<?php 
namespace Users;

Class Schema
{
	public $_colprefix = 'user_';

	public $id;
	public $email = array('label' => 'User Email', 'rules' => 'required|string|maxLen[60]|validEmail');
	public $password = array('label' => 'User Password', 'rules' => 'required|string|maxLen[255]|minLen[6]');
	public $confirm_password = array('label' => 'Confirm Password', 'rules' => 'required|matches[password]');
	public $agreement = array('label' => 'User Agreement', 'rules' => 'integer|required');

}

/* End of file users.php */
/* Location: .modules/models/schema/users.php */