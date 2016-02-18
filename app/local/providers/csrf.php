<?php

return array(

    'params' => [
        'protection' => true,
        'token' => [
            'salt' => 'create-your-salt-string',
            'name' => 'csrf_token',
            'refresh' => 30,
        ],
    ],
);
