<?php
/*
|--------------------------------------------------------------------------
| Mail Class Configuration
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'useragent' => 'Obullo', //  The "user agent".
    'mailpath'  => '/usr/sbin/sendmail',  //  The server path to Sendmail.
    
    'smtpHost' =>  'smtp.mandrillapp.com',     // SMTP Server Address.
    'smtpUser' => 'obulloframework@gmail.com', // SMTP Username.
    'smtpPass' => 'BIK8O7xt1Kp7aZyyQ55uOQ',    // SMTP Password.
    'smtpPort' => '587', // SMTP Port.
    'smtpTimeout' => '5' , // SMTP Timeout (in seconds).

    'wordwrap' => true, // "true" or "false" (boolean) Enable word-wrap.
    'wrapchars' => 76,   // Character count to wrap at.
    'mailtype' => 'html',    // text or html Type of mail. If you send HTML email you must send it as a complete web page. 
                             // Make sure you don't have any relative links or relative image paths otherwise they will not work.
    'charset' => 'utf-8',    // Character set (utf-8, iso-8859-1, etc.).
    'validate' => false,     // "true" or "false" (boolean) Whether to validate the email address.
    'priority' =>  3,        // 1, 2, 3, 4, 5   Email Priority. 1 = highest. 5 = lowest. 3 = normal.
    'crlf'  => "\n",         //  "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
    'newline' =>  "\n",      // "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
    'bccBatchMode' =>  false,   // true or false (boolean) Enable BCC Batch Mode.
    'bccBatchSize' => 200    // None  Number of emails in each BCC batch.
);

/* End of file mail.php */
/* Location: .app/env/local/mail.php */