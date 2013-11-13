<?php 
namespace Schema;

Class Users
{
	public $_colprefix = 'user_';

	public $id;
	public $email = array('label' => 'User Email', 'rules' => 'required|_string(255)|minLen(6)|validEmail');
	public $password = array('label' => 'User Password', 'rules' => 'required|_string(255)|minLen(6)');
}

/* End of file users.php */
/* Location: .modules/schemas/users.php */