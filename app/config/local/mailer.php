<?php

return array(

    /**
     * Defaults
     *
     * Enabled : On / Off mailer service
     * Useragent : Mailer agent.
     * Validate : Whether to validate the email addresses.
     */
    'default' => [
        'enabled' => true,
        'useragent' => 'Obullo Mailer',
        'validate' => false,
    ],

    /**
     * Message body
     * 
     * Charset : Character set (utf-8, iso-8859-1, etc).
     * Priority : 1, 2, 3, 4, 5   Email Priority. 1 = highest. 5 = lowest. 3 = normal.
     * Wordwrap : Enable / disabled word-wrap.
     * Wrapchars : Character count to wrap at.
     * Mailtype : Text or html Type of mail. If you send HTML email you must send it as a complete web page. 
     * Crlf : "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
     * Newline : "\r\n" or "\n" or "\r"  Newline character. (Use "\r\n" to comply with RFC 822).
     */
    'message' => [
        'charset' => 'utf-8',
        'priority' =>  3,
        'wordwrap' => true,
        'wrapchars' => 76,
        'mailtype' => 'html',
        'crlf'  => "\n",
        'newline' =>  "\n",
    ]
);