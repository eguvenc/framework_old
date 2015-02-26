<?php
/*
|--------------------------------------------------------------------------
| Application middlewares
|--------------------------------------------------------------------------
| Obullo allows you to run code different stages during the handling of a request through middlewares:
|	
| 	- Application middlewares are triggered independently of the current handled request;
|	- Route middlewares are triggered when their associated route is matched.
| 
*/
$c['app']->middleware(new Http\Middlewares\Request);

/*
|--------------------------------------------------------------------------
| Translations
|--------------------------------------------------------------------------
| Detect locale and set.
*/
// $c['app']->middleware(new Http\Middlewares\Translation);


/* End of file middlewares.php */
/* Location: .middlewares.php */