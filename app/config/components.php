<?php

/*
| -------------------------------------------------------------------
| REPLACE THE COMPONENTS
| -------------------------------------------------------------------
| This file specifies components of Obullo that you may replace them with
| third party packages.
|
| We get the components using getComponentOf('component');
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
| $component = getComponentOf('db');
|
*/

$components['db']     = 'Database_Pdo';  // Database_Pdo package is a component of Db package. More components ? ( Mongo , Database .. etc. )
$components['vi']     = 'View';          // View Package is a component of Vi package
$components['log']    = 'Log_Write';     // Log_Write Package is a component of Log package
$components['error']  = 'Exceptions';    // Exceptions Package is a component of Error package
$components['lang']   = 'Locale';        // Locale Package is a component of Obullo package ( lang() function use it. )
$components['config'] = 'Config';        // Config Package is a component of Config package
$components['obullo'] = 'Obullo';        // Config Package is a component of Config package