<?php

/*
|--------------------------------------------------------------------------
| Package.json ARRAY output file 
|--------------------------------------------------------------------------
| This file and /config folder needs 777 write permissions.
| 
| Prototype: 
|
|   $packages['key'] = value;
| 
*/

$packages = array(
    'name' => 'Obullo',
    'version' => '2.0',
    'dependencies' => array(
        'error' => '0.0.1',
        'input' => '0.0.1',
        'log' => '0.0.1',
        'router' =>  '0.0.1',
        'uri' => '0.0.1',
        'security' => '0.0.1', 
        'database_pdo' => '0.0.1',
        'view' => '0.0.1',
        'config' => array( 'repo' => 'https =>//github.com/eguvenc/config.git', 'version' =>  '0.0.1' ),
        'task'  => '0.0.1',
        'auth'  => '0.0.1'
    )
  );