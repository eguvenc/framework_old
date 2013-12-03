<?php 

$users = array(
  '*' => array('colprefix' => 'user_'),

  'id' => '',
  'email' => array(
  	'label' => 'User Email',
  	'types' => '_not_null|_varchar(255)',
  	'rules' => 'required|minLen(6)|validEmail',
  	),
  'password' => array(
  	'label' => 'User Password',
  	'types' => '_not_null|_varchar(255)',
  	'rules' => 'required|minLen(6)',
  	),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */