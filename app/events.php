<?php
/*
|--------------------------------------------------------------------------
| Global Events
|--------------------------------------------------------------------------
| This file specifies the your application global events.
*/
/*
|--------------------------------------------------------------------------
| Request - Response Handler
|--------------------------------------------------------------------------
*/

$c['event']->subscribe(new Event\UserRequestHandler($c));


/* End of file events.php */
/* Location: .events.php */