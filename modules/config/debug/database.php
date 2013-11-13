<?php

/*
|--------------------------------------------------------------------------
| Database Settings
|--------------------------------------------------------------------------
| Put your database configurations here and decide your db variable name.
| You database variable will available like following the example.
| 
|   $this->db->method();
| 
| $database['db']  default variable name is 'db'.
| 
| Prototypes: 
|
|   $database['db']['hostname']  = 'localhost';
|   $database['db']['options']   = array( PDO::ATTR_PERSISTENT => false ); 
| 
*/

$database['db']['hostname']  = 'localhost';
$database['db']['username']  = 'root';
$database['db']['password']  = '123456';
$database['db']['database']  = 'obullo';
$database['db']['driver']    = 'mysql';
$database['db']['prefix']    = '';
$database['db']['dbh_port']  = '';
$database['db']['char_set']  = 'utf8';
$database['db']['dsn']       = '';
$database['db']['options']   = array();


/* End of file database.php */
/* Location: .app/config/debug/database.php */