<?php

return array(

    // 'drivers' => [
    //     'mail' => '\Obullo\Mailer\Transport\Mail',
    //     'smtp' => '\Obullo\Mailer\Transport\Smtp',
    //     'sendmail' => '\Obullo\Mailer\Transport\Sendmail',
    //     'mandrill' => '\Obullo\Mailer\Transport\Mandrill',
    // ],

    'settings' => [
        'useragent' => 'Obullo Mailer',  // Mailer "user agent".
        'wordwrap' => true,              // "true" or "false" (boolean) Enable word-wrap.
        'wrapchars' => 76,               // Character count to wrap at.
        'mailtype' => 'html',            // text or html Type of mail. If you send HTML email you must send it as a complete web page. 
        'charset' => 'utf-8',            // Character set (utf-8, iso-8859-1, etc.).
        'validate' => false,             // Whether to validate the email address.
        'priority' =>  3,                // 1, 2, 3, 4, 5   Email Priority. 1 = highest. 5 = lowest. 3 = normal.
        'crlf'  => "\n",                 //  "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
        'newline' =>  "\n",              // "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
    ],

    'queue' => [
        'channel' => 'mail',             // Queue Mailer channel name
        'route' => 'mailer.1',           // Queue Mailer route name
        'worker' => 'Workers\Mailer',    // Queue Worker Class
    ],

    'mandrill' => [
        'url' => 'https://mandrillapp.com/api/1.0/messages/send.json',
        'key' => 'BIK8O7xt1Kp7aZyyQ55uOQ',   // Mandrill api key
        'pool' => 'Main Pool',               // The name of the dedicated ip pool that should be used to send the message.
    ]

);

/* End of file mailer.php */
/* Location: .config/env/local/mailer.php */