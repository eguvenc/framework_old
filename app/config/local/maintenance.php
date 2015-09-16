<?php

return array(

    'root' => [
        'maintenance' => 'up',
    ],
    'mydomain' => [
        'maintenance' => 'up',
        'regex' => 'test.*\\d.framework',
    ],
    'subdomain' => [
        'maintenance' => 'up',
        'regex' => 'sub.domain.com',
    ],
);
