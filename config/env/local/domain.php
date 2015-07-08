<?php

return array(

    'root' => [
        'maintenance' => 'up',
        'regex' => null,
    ],
    'mydomain.com' => [
        'maintenance' => 'up',
        'regex' => '^framework$',
    ],
    'sub.domain.com' => [
        'maintenance' => 'up',
        'regex' => '^sub.domain.com$',
    ],
);

/* End of file */
/* Location: .config/env/local/domain.php */