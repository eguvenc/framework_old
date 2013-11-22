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
| $scheme = array(
|
|	 'default' => function($file)
|	 {
		 $this->set('header', getInstance()->tpl('header',false))
|	     $this->set('content', $file);
|	     $this->set('footer', getInstance()->tpl('footer',false));
|	 },
| );
|
*/

$scheme = array(

	'default' => function($file)
	{
	    $this->set('content', $file);
	    $this->set('footer', getInstance()->tpl('footer',false));
	},
);

/* End of file scheme.php */
/* Location: ./app/config/scheme.php */