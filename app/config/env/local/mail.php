<?php

return array(

    // Provider ı driver olarak değiştir.
    'default' => array(
        'provider' => 'mandrill',    // Choose provider ( mandrill, mailgun, smtp, mail, sendmail )
        'handler' => '\\Obullo\Mail\Transport\Queue',
    ),

    // 'handlers' => array(
    //     'queue' => '\\Obullo\Mail\Transport\Queue',
    //     'mail' => '\\Obullo\Mail\Send\Protocol\Mail',
    //     'sendmail' => '\\Obullo\Mail\Send\Protocol\Sendmail',
    //     'smtp' => '\\Obullo\Mail\Send\Protocol\Smtp',
    //     'mandrill' => '\\Obullo\Mail\Transport\Mandrill',
    //     'mailgun' => '\\Obullo\Mail\Transport\Mailgun',
    //     'yourmailer' => '\\Mail\Transport\YourMailer'     // You can create your own mailer using Mail/Transport/TransportInterface.php
    // ),

    'send' => array(
        
        'settings' => array(
            'useragent' => 'Framework Mailer',  //  The "user agent".
            'wordwrap' => true,       // "true" or "false" (boolean) Enable word-wrap.
            'wrapchars' => 76,        // Character count to wrap at.
            'mailtype' => 'html',     // text or html Type of mail. If you send HTML email you must send it as a complete web page. 
                                      // Make sure you don't have any relative links or relative image paths otherwise they will not work.
            'charset' => 'utf-8',     // Character set (utf-8, iso-8859-1, etc.).
            'validate' => false,      // "true" or "false" (boolean) Whether to validate the email address.
            'priority' =>  3,         // 1, 2, 3, 4, 5   Email Priority. 1 = highest. 5 = lowest. 3 = normal.
            'crlf'  => "\n",          //  "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
            'newline' =>  "\n",       // "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
            'bccBatchMode' =>  false, // true or false (boolean) Enable BCC Batch Mode.
            'bccBatchSize' => 200,    // None  Number of emails in each BCC batch.
        ),

        'from' => array(
            'name'  => 'Admin',
            'email' => 'admin@example.com',   // Update your default sender email
            'address' => 'Admin <admin@example.com>'
        ),

        'protocol' => array(
            'mail' => array(

            ),
            'sendmail' => array(
                'mailpath'  => '/usr/sbin/sendmail',  //  The server path to Sendmail.
            ),
            'smtp' => array(
                'host' => 'smtp.mandrillapp.com',          // SMTP Server Address.
                'user' => $c['env']['MANDRILL_USERNAME'],  // SMTP Username.
                'pass' => $c['env']['MANDRILL_API_KEY'],   // SMTP Password.
                'port' => '587',                           // Port.
                'timeout' => '5' ,                         // Timeout (in seconds).
            ),
        ),
        
        'transport' => array(
            'mandrill' => array(
                'key' => $c['env']['MANDRILL_API_KEY'],  // Mandrill api key
                'ip_pool' => 'Main Pool',                // The name of the dedicated ip pool that should be used to send the message.
            ),
            'mailgun' => array(

            )
        ),
        
        'queue' => array(
            'mailer' => 'mandrill',             // Default mailer   // mandrill, mailgun, smtp, sendmail, mail ..
            'channel' => 'Mail',                // Queue Mailer channel name
            'route' => gethostname().'.Mailer', // Queue Mailer route name
            'worker' => 'Workers\Mailer',       // Queue Worker Class
        )

    ),
);

/* End of file mail.php */
/* Location: .app/config/env/local/mail.php */