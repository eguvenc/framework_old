<?php
/*
|--------------------------------------------------------------------------
| Global Configurations
|--------------------------------------------------------------------------
| This file specifies the your application global methods.
*/

/*
|--------------------------------------------------------------------------
| Application Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "php task app down" command gives you the ability to put an application
| into maintenance mode.
|
*/
$c['app']->func(
    'app.down',
    function ($params) use ($c) {
        $c->load('response')->setHttpResponse(503)->sendOutput($c->load('view')->template('maintenance'));
        die;
    }
);
/*
|--------------------------------------------------------------------------
| Service Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "php task service down" command gives you the ability to put a service
| into maintenance mode.
|
*/
$c['app']->func(
    'service.down',
    function ($params) use ($c) {
        $c->load('response')->setHttpResponse(503);
        echo 'Service Unavailable !';
        die;
    }
);


/* End of file global.php */
/* Location: .global.php */