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
| 
| Priority of middlewares: Add your middleware to end if you need run it at the top.
*/
/*
|--------------------------------------------------------------------------
| Request
|--------------------------------------------------------------------------
*/
$c['app']->middleware(new Http\Middlewares\Request);