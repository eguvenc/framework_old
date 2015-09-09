<?php

return array(

    'root' => [
        'maintenance' => 'up',
    ],
    'mydomain' => [
        'maintenance' => 'up',
        'namespace' => 'Welcome',
        'regex' => '^framework$',
    ],
    'sub.domain' => [
        'maintenance' => 'up',
        'namespace' => null,
        'regex' => '^sub.domain.com$',
    ],
);
