<?php

return array(

    'ip' => array(
        
        'enabled' => 1,
        'limit' => array(
            'interval' => array('amount' => 300, 'maxRequest' => 7),  // 300 seconds / 7 times
            'hourly' => array('amount' => 1, 'maxRequest' => 15),     // 1 hour / 15 times
            'daily' => array('amount' => 1, 'maxRequest' => 50),      // 1 day / 50 times
        ),
        'ban' => array(
            'status' => 1,          // If ban status disabled don't do ban
            'expiration' => 86400,  // If ban status enablead wait for this time
        ),
    ),

    'username' => array(

        'enabled' => 1,
        'limit' => array(
            'interval' => array('amount' => 300, 'maxRequest' => 7),  // 300 seconds / 7 times
            'hourly' => array('amount' => 1, 'maxRequest' => 15),     // 1 hour / 15 times
            'daily' => array('amount' => 1, 'maxRequest' => 50),      // 1 day / 50 times
        ),
        'ban' => array(
            'status' => 1,
            'expiration' => 86400,
        ),
    ),

    'mobile' => array(

        'enabled' => 1,
        'limit' => array(
            'interval' => array('amount' => 300, 'maxRequest' => 7),  // 300 seconds / 7 times
            'hourly' => array('amount' => 1, 'maxRequest' => 15),     // 1 hour / 15 times
            'daily' => array('amount' => 1, 'maxRequest' => 50),      // 1 day / 50 times
        ),
        'ban' => array(
            'status' => 1,
            'expiration' => 86400,
        ),
    )

);

/* End of file rate.php */
/* Location: .app/config/rate.php */