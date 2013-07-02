<?php

/*
|--------------------------------------------------------------------------
| Database Settings
|--------------------------------------------------------------------------
| Choose your database layer
  Put your static database configurations here and decide your db variable
| name. 
| You will use it in your application using $this->db .
| 
| $database['db']['option']  db variable name ( default is 'db' )
| 
| Prototype: 
|   $database['db']['options']  = array( PDO::ATTR_PERSISTENT => FALSE ); 
| 
*/
$database['db']['hostname']  = 'localhost';
$database['db']['username']  = 'root';
$database['db']['password']  = '123456';
$database['db']['database']  = 'obullo';
$database['db']['dbdriver']  = 'mysql';
$database['db']['dbprefix']  = '';
$database['db']['swap_pre']  = '';
$database['db']['dbh_port']  = '';
$database['db']['char_set']  = 'utf8';
$database['db']['dsn']       = '';
$database['db']['options']   = array();


/* End of file database.php */
/* Location: .app/config/database.php */