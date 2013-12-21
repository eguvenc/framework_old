<?php

/*
| -------------------------------------------------------------------------
| Schema
| -------------------------------------------------------------------------
| This file lets you define SQL suffix to creating sql tables using 
| Schema Sql Creator Class without hacking it.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
| $schema = array(
|
|	'table_suffix' => ' ENGINE=InnoDB DEFAULT CHARSET=utf8;',
|
| );
|
*/

$schema = array(

	'mysql_table_suffix' => ' ENGINE=InnoDB DEFAULT CHARSET=utf8;',  // Sql creator table suffix
);


/* End of file schema.php */
/* Location: ./app/config/schema.php */