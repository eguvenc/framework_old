<?php

/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Database Variables
|
*/
$database = array(

	'db' => new Pdo_Mysql(array(
		'variable' => 'db',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '123456',
		'database' => 'test',
		'driver'   => '',
		'prefix'   => '',
		'dbh_port' => '',
		'char_set' => 'utf8',
		'dsn' 	   => '',
		'options'  => array() // array( PDO::ATTR_PERSISTENT => false ); 
		)),
	
);

/* End of file database.php */
/* Location: .app/config/debug/database.php */