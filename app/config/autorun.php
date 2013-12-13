<?php

/*
| -------------------------------------------------------------------
| Auto-Run File ( Run in Controller )
| -------------------------------------------------------------------
| This file specifies which functions should be run by default 
| in __construct() level of the controller.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are run by default.This file lets
| you globally define which systems you would like run with every
| request.
|
| Note: If you need a bootstrap level autorun functionality look 
| for the Hooks package.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| $autorun = array(
|	'controller' => function(){
|		$this->lingo->load('spanish');
|	}
| );
|
*/
$autorun = array(
	'controller' => function(){
		new Sess;
	}
);

/* End of file autorun.php */
/* Location: .app/config/autorun.php */