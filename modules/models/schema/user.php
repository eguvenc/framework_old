<?php
namespace User;

Class Schema
{
	/* Config */
	public $_colprefix = 'user_';

    /* Fields */
    public $id;

    public $password = array('rules' => 'required|noSpace|minLen[6]|maxLen[20]');
    public $confirm_password = array('label' => 'Confirm Password', 'rules' => 'required|matches[password]');
    public $email    = array('label' => 'Email Address', 'rules' => 'required|trim|validEmail');
    public $agreement = array('label' => 'User Agreement', 'rules' => 'integer|required');
}

/* End of file user.php */
/* Location: .modules/models/schema/user.php */