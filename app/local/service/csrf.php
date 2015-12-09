<?php

return array(

        /**
         * Csrf protection
         */

        'params' => [
        
            'protection' => false,
            'token' => [
                'name' => 'csrf_token',
                'refresh' => 30,
            ],   
        ]
    );