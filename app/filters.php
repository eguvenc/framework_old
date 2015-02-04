<?php
/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Maintenance
|--------------------------------------------------------------------------
| Domain under maintenance control
*/
$c['router']->filter('maintenance', 'Http\Filters\MaintenanceFilter', 'before');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Authentication filter
*/
$c['router']->filter('auth', 'Http\Filters\AuthFilter', 'before');

/*
|--------------------------------------------------------------------------
| Csrf
|--------------------------------------------------------------------------
| Cross site request forgery check filter
*/
$c['router']->filter('csrf', 'Http\Filters\CsrfFilter', 'before');

/*
|--------------------------------------------------------------------------
| Method Not Allowed
|--------------------------------------------------------------------------
| Checks http request methods
*/
$c['router']->filter('methodNotAllowed', 'Http\Filters\RequestNotAllowedFilter', 'before');

/*
|--------------------------------------------------------------------------
| Application Before Filter
|--------------------------------------------------------------------------
| A before application filter allows you to execute tasks before the controller is executed
*/
$c['app']->filter('Http\Filters\RequestFilter', 'before');
/*
|--------------------------------------------------------------------------
| Application Finish Filter
|--------------------------------------------------------------------------
| A finish application filter allows you to execute tasks after the Response has been sent to the client
*/
$c['app']->filter('Http\Filters\RequestFilter', 'finish');



/* End of file filters.php */
/* Location: .filters.php */