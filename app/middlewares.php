<?php


return array(
    
    'global' => array(   // global middlewares
        'Http\Middlewares\RequestMethod'

    ),

    'routed' => array(
        'maintenance' =>  'Http\Filters\UnderMaintenance',
    )

);