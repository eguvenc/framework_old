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
        'auth'  => array('version' => '0.0.1', 'component' => 'library'),
        'config' => array( 'repo' => 'https =>//github.com/eguvenc/config.git', 'version' =>  '0.0.1', 'component' => 'library'),
        'benchmark' => array('version' => '0.0.1', 'component' => 'library'),
        'error' => array('version' => '0.0.1', 'component' => 'helper'),
        'exceptions' => array('version' => '0.0.1', 'component' => 'library'),
        'html' => array('version' => '0.0.1', 'component' => 'helper'),
        'input' => array('version' => '0.0.1', 'component' => 'library'),
        'log' => array('version' => '0.0.1', 'component' => 'helper'),
        'locale' => array('version' => '0.0.1', 'component' => 'library'),
        'output'  => array('version' => '0.0.1', 'component' => 'library'),
        'router' =>  array('version' => '0.0.1', 'component' => 'library'),
        'request' => array('version' => '0.0.1', 'component' => 'helper'),
        'security' => array('version' => '0.0.1', 'component' => 'helper'), 
        'session' => array('version' => '0.0.1', 'component' => 'helper'), 
        'string' => array('version' => '0.0.1', 'component' => 'helper'), 
        'task'  => array('version' => '0.0.1', 'component' => 'helper'),
        'uri' => array('version' => '0.0.1', 'component' => 'library'),
        'url' => array('version' => '0.0.1', 'component' => 'helper'),
        'view' => array('version' => '0.0.1', 'component' => 'helper'),
    )
  );