<?php 

$users = array(
	'*' => array(),
	
	'id' => array(
		'label' => 'Id',
		'types' => '_not_null|_primary_key|_unsigned|_int(11)|_auto_increment|_key',
		'rules' => '',
		),
	'cities' => array(
		'label' => 'Cities',
		'types' => '_not_null|_default("istanbul")|_enum',
		'_enum' => array('istanbul','ankara','izmir'),
		'rules' => '',
		),
	'datetime' => array(
		'label' => 'Datetime',
		'types' => '_not_null|_datetime',
		'rules' => '',
		),
	'email' => array(
		'label' => 'Email',
		'types' => '_null|_char(160)',
		'rules' => '',
		),
	'last_name' => array(
		'label' => 'Last Name',
		'types' => '_null|_char(60)',
		'rules' => '',
		),
	'first_name' => array(
		'label' => 'First Name',
		'types' => '_null|_text',
		'rules' => '',
		),
	'password' => array(
		'label' => 'Password',
		'types' => '_null|_char(100)',
		'rules' => '',
		),
	'price' => array(
		'label' => 'Price',
		'types' => '_not_null|_float(10,2)',
		'rules' => '',
		),
	'total_price' => array(
		'label' => 'Total Price',
		'types' => '_not_null|_double',
		'rules' => '',
		),
	'date' => array(
		'label' => 'Date',
		'types' => '_not_null|_default("0000-00-00")|_date',
		'rules' => '',
		),
	'time' => array(
		'label' => 'Time',
		'types' => '_not_null|_default("00:00:00")|_time',
		'rules' => '',
		),
	'timestemp' => array(
		'label' => 'Timestemp',
		'types' => '_not_null|_default(CURRENT_TIMESTAMP)|_timestamp',
		'rules' => '',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */