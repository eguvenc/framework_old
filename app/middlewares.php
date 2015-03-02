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
| IMPORTANT: Obullo automatically assign route middlewares when their associated route is matched. 
| There is no need to assign them in here.
*/
/*
|--------------------------------------------------------------------------
| Translations
|--------------------------------------------------------------------------
| Detect locale and set.
*/
$c['app']->middleware(new Http\Middlewares\Translation);

/*
|--------------------------------------------------------------------------
| Request
|--------------------------------------------------------------------------
| Sanitize requests
*/
$c['app']->middleware(new Http\Middlewares\Request);


/* End of file middlewares.php */
/* Location: .middlewares.php */