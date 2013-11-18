<?php 

$users = array(
  '_settings' => array('colprefix' => 'user_'),

  'id' => '',
  'email' => array('label' => 'User Email','rules' => 'required|minLen(6)|_string(255)|validEmail'),
  'password' => array('label' => 'User Password', 'rules' => 'required|_string(255)|minLen(6)'),
  
  'cities' => array(
  	'label' => 'Cities',
      '_enum' => function(){
			return array('London','Tokyo','Paris','New York','Berlin','Istanbul');
      },
    'rules' => 'required|_enum|minLen(3)'),
);

/* End of file users.php */
/* Location: .app/schemas/users.php */