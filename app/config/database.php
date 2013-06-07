<?php
defined('BASE') or exit('Access Denied!'); 

/*
|--------------------------------------------------------------------------
| Database Settings
|--------------------------------------------------------------------------
| Put your static database configurations here and decide your db variable
| name. You will use it in the application with db variable e.g. $this->db .
| 
| $database['db']['option']  db variable name ( default is 'db' )
| 
| Prototype: 
|
|   $database['db']['options'] = array( PDO::ATTR_PERSISTENT => FALSE); 
| 
*/
$database['db']['hostname']  = 'localhost';
$database['db']['username']  = 'root';
$database['db']['password']  = '';
$database['db']['database']  = '';
$database['db']['dbdriver']  = 'mysql';
$database['db']['dbprefix']  = '';
$database['db']['swap_pre']  = '';
$database['db']['dbh_port']  = '';
$database['db']['char_set']  = 'utf8';
$database['db']['dsn']       = '';
$database['db']['options']   = array();

$database['db2']['hostname']  = 'localhost';
$database['db2']['username']  = 'root';
$database['db2']['password']  = '';
$database['db2']['database']  = '';
$database['db2']['dbdriver']  = 'mysql';
$database['db2']['dbprefix']  = '';
$database['db2']['swap_pre']  = '';
$database['db2']['dbh_port']  = '';
$database['db2']['char_set']  = 'utf8';
$database['db2']['dsn']       = '';
$database['db2']['options']   = array();

/* End of file database.php */
/* Location: .app/config/database.php */