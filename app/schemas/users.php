<?php 

$users = array(
	'*' => array('colprefix' => 'user_'),
	
	'id' => array(
		'label' => 'User Id',
		'types' => '_primary_key',
		'rules' => '',
		),
	'email' => array(
		'label' => 'User Email',
		'types' => '_not_null|_varchar(255)',
		'rules' => '',
		),
	'password' => array(
		'label' => 'User Password',
		'types' => '_not_null|_varchar(255)',
		'rules' => '',
		),
	'id' => array(
		'label' => 'Id',
		'types' => '_unsigned',
		'rules' => '',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */