<?php

// Email English
//-----------------------------------------------------------------

return array(
    'OBULLO:MAIL:MUST_BE_ARRAY' => 'The email validation method must be passed an array.',
    'OBULLO:MAIL:INVALID_ADDRESS' => 'Invalid email address: %s',
    'OBULLO:MAIL:ATTACHMENT_MISSING' => 'Unable to locate the following email attachment: %s',
    'OBULLO:MAIL:ATTACHMENT_UNREADABLE' => 'Unable to open this attachment: %s',
    'OBULLO:MAIL:NO_RECIPIENTS' => 'You must include recipients: To, Cc, or Bcc',
    'OBULLO:MAIL:SEND_FAILURE_PHPMAIL' => 'Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.',
    'OBULLO:MAIL:SEND_FAILURE_SENDMAIL' => 'Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.',
    'OBULLO:MAIL:SEND_FAILURE_SMTP' => 'Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.',
    'OBULLO:MAIL:SENT' => 'Your message has been successfully sent using the following protocol: %s',
    'OBULLO:MAIL:NO_SOCKET' => 'Unable to open a socket to Sendmail. Please check settings.',
    'OBULLO:MAIL:NO_HOSTNAME' => 'You did not specify a SMTP hostname.',
    'OBULLO:MAIL:SMTP_ERROR' => 'The following SMTP error was encountered: %s',
    'OBULLO:MAIL:NO_SMTP_UNPW' => 'Error: You must assign a SMTP username and password.',
    'OBULLO:MAIL:FAILED_SMTP_LOGIN' => 'Failed to send AUTH LOGIN command. Error: %s',
    'OBULLO:MAIL:SMTP_AUTH_UN' => 'Failed to authenticate username. Error: %s',
    'OBULLO:MAIL:SMTP_AUTH_PW' => 'Failed to authenticate password. Error: %s',
    'OBULLO:MAIL:SMTP_DATA_FAILURE' => 'Unable to send data: %s',
    'OBULLO:MAIL:EXIT_STATUS' => 'Exit status code: %s',
);