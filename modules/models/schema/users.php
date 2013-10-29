<?php 
namespace Schema;

Class Users
{
	public $_colprefix = 'user_';

	public $id;
	public $email = array('label' => 'User Email', 'rules' => 'required|string|maxLen[60]|validEmail');
	public $password = array('label' => 'User Password', 'rules' => 'required|string|maxLen[255]|minLen[6]');
}

/* End of file users.php */
/* Location: .modules/models/schema/users.php */