<?php 
defined('BASE') or exit('Access Denied!'); 
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|     example.com/module/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| Chapters / General Topics / URI Routing 
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are one reserved route:
|
|    $route['default_controller'] = 'welcome/start';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "start" class
| would be loaded.
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/ 

/*
|--------------------------------------------------------------------------
| Default Directory Controller
|--------------------------------------------------------------------------
|
| This is the default controller, application call it as default controller
| You must provide directory separator because of obullo always use 
| this pattern example.com/directory/class/method/id/ 
|
*/
$routes['default_controller']    = 'welcome/index';  // or welcome/controller/method name
$routes['404_override']          = '';

/*
|--------------------------------------------------------------------------
| Controller Default Index Method
|--------------------------------------------------------------------------
|
| This is controller default index method for all controllers.You should
| configure it before the first run of your application.
|
*/
$routes['index_method']          = "index";


/* End of file routes.php */
/* Location: .app/config/routes.php */