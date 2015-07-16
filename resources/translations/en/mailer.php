<?php

return array(
    'OBULLO:MAILER:MUST_BE_ARRAY' => 'The email validation method must be passed an array.',
    'OBULLO:MAILER:INVALID_ADDRESS' => 'Invalid email address: %s',
    'OBULLO:MAILER:ATTACHMENT_MISSING' => 'Unable to locate the following email attachment: %s',
    'OBULLO:MAILER:ATTACHMENT_UNREADABLE' => 'Unable to open this attachment: %s',
    'OBULLO:MAILER:NO_RECIPIENTS' => 'You must include recipients: To, Cc, or Bcc',
    'OBULLO:MAILER:SEND_FAILURE_PHPMAIL' => 'Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.',
    'OBULLO:MAILER:SEND_FAILURE_SENDMAIL' => 'Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.',
    'OBULLO:MAILER:SEND_FAILURE_SMTP' => 'Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.',
    'OBULLO:MAILER:SENT' => 'Your message has been successfully sent using the following protocol: %s',
    'OBULLO:MAILER:NO_SOCKET' => 'Unable to open a socket to Sendmail. Please check settings.',
    'OBULLO:MAILER:NO_HOSTNAME' => 'You did not specify a SMTP hostname.',
    'OBULLO:MAILER:SMTP_ERROR' => 'The following SMTP error was encountered: %s',
    'OBULLO:MAILER:NO_SMTP_UNPW' => 'Error: You must assign a SMTP username and password.',
    'OBULLO:MAILER:FAILED_SMTP_LOGIN' => 'Failed to send AUTH LOGIN command. Error: %s',
    'OBULLO:MAILER:SMTP_AUTH_UN' => 'Failed to authenticate username. Error: %s',
    'OBULLO:MAILER:SMTP_AUTH_PW' => 'Failed to authenticate password. Error: %s',
    'OBULLO:MAILER:SMTP_DATA_FAILURE' => 'Unable to send data: %s',
    'OBULLO:MAILER:EXIT_STATUS' => 'Exit status code: %s',
    'OBULLO:MAILER:API_ERROR' => 'Mailer api failed with following parameters: url=%s, error=%s',
    'OBULLO:MAILER:QUEUE_FAILED' => 'Mailer class queue failed with following parameters: route=%s',
);