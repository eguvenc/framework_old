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
| Domain under maintenance filter
*/
$c['router']->filter('maintenance', 'Http\Filters\MaintenanceFilter', 'before');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Authentication filters
*/
$c['router']->filter('auth', 'Http\Filters\AuthFilter', 'before');
$c['router']->filter('guest', 'Http\Filters\GuestFilter', 'before');
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
| Http request route methods validate filter
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