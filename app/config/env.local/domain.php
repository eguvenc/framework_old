<?php

return array(

    'root' => array(
        'maintenance' => 'up',
        'regex' => null,
    ),
    'mydomain.com' => array(
        'maintenance' => 'up',
        'regex' => '^framework$',
    ),
    'sub.domain.com' => array(
        'maintenance' => 'up',
        'regex' => '^sub.domain.com$',
    ),
);

/* End of file */
/* Location: .app/config/env.local/domain.php */