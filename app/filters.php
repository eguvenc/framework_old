<?php
/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
| This file specifies the your application filters.
*/
/*
|--------------------------------------------------------------------------
| Maintenance
|--------------------------------------------------------------------------
| App maintenance control filter
*/
$c['router']->filter('maintenance', 'Http\Filters\MaintenanceFilter');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Authentication control filter
*/
$c['router']->filter('auth', 'Http\Filters\AuthFilter');

/*
|--------------------------------------------------------------------------
| Csrf
|--------------------------------------------------------------------------
| Cross site request forgery check filter
*/
$c['router']->filter('csrf', 'Http\Filters\CsrfFilter');

/*
|--------------------------------------------------------------------------
| Activity
|--------------------------------------------------------------------------
| Authorized user activity check filter
*/
$c['router']->filter('activity', 'Http\Filters\ActivityFilter');


/* End of file filters.php */
/* Location: .filters.php */