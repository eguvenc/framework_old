<?php

/*
| -------------------------------------------------------------------------
| Schemes
| -------------------------------------------------------------------------
| This file lets you define "schemes" to extend views without hacking
| the view function. Please see the docs for info:
|
|	@see docs/advanced/schemes
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
|	$scheme['general'] = function(){
|
|		$this->set('header', tpl('header',false));
|		$this->set('footer', tpl('footer',false));
|
|	};
|
*/

$scheme['general'] = function($filename){

	$this->set('header', tpl('header',false));
	$this->set('content', view($filename, false));
	$this->set('footer', tpl('footer',false));
	
};

/* End of file scheme.php */
/* Location: ./app/config/scheme.php */