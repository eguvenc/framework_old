<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Form Messages
    | -------------------------------------------------------------------
    | This file contains form messages configurations.
    | It is used by Form Class to set messages HTML template or attributes.
    | Array keys are predefined in form class file.
    |
    | Note : Default CSS classes brought from getbootstrap.com
    |
    */
    'form' => [

        'message' => '<div class="{class}">{icon}{message}</div>',
        'error'  => [
            'class' => 'alert alert-danger', 
            'icon' => '<span class="glyphicon glyphicon-remove-sign"></span> '
        ],
        'success' => [
            'class' => 'alert alert-success', 
            'icon' => '<span class="glyphicon glyphicon-ok-sign"></span> '
        ],
        'warning' => [
            'class' => 'alert alert-warning', 
            'icon' => '<span class="glyphicon glyphicon-exclamation-sign"></span> '
        ],
        'info' => [
            'class' => 'alert alert-info', 
            'icon' => '<span class="glyphicon glyphicon-info-sign"></span> '
        ],
    ],

    'flash' => [
    
        'message' => '<div class="{class}">{icon}{message}</div>',
        'error'  => [
            'class' => 'alert alert-danger', 
            'icon' => '<span class="glyphicon glyphicon-remove-sign"></span> '
        ],
        'success' => [
            'class' => 'alert alert-success', 
            'icon' => '<span class="glyphicon glyphicon-ok-sign"></span> '
        ],
        'warning' => [
            'class' => 'alert alert-warning', 
            'icon' => '<span class="glyphicon glyphicon-exclamation-sign"></span> '
        ],
        'info' => [
            'class' => 'alert alert-info', 
            'icon' => '<span class="glyphicon glyphicon-info-sign"></span> '
        ],
    ]

);