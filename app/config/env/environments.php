<?php
/*
|--------------------------------------------------------------------------
| Environments
|--------------------------------------------------------------------------
*/
return array(
    'local' => array (
        'server' => array(
            'hostname' => array(
                'ersin-desktop',
                'someone.computer',
                'anotherone.computer',
            ),
            'ip' => array(
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
            ),
        ),
    ),
    'test' => array (
        'server' => array(
            'hostname' => array(
                'localhost.test',
            ),
            'ip' => array(),
        ),
    ),
    'prod' => array (
        'server' => array(
            'hostname' => array(
                'localhost.production',
            ),
            'ip' => array(),
        ),
    ),
);

// END environments.php File
/* End of file environments.php

/* Location: .app/config/env/environments.php */