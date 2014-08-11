<?php
/*
|--------------------------------------------------------------------------
| Global Configurations
|--------------------------------------------------------------------------
| This file specifies the your application global methods.
*/

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "php task app down" command gives you the ability to put an application
| into maintenance mode.
|
*/
$c['app']->down(
    function () use ($c) {
        $c->load('response')->setHttpResponse(503)->sendOutput($c->load('view')->template('maintenance'));
        die;
    }
);


/* End of file global.php */
/* Location: .global.php */