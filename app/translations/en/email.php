<?php

// Email English
//-----------------------------------------------------------------


// $translate['OBULLO:EMAIL:REQUIRED'] = 'The %s field is required.';

$translate[i18n_Error_Email::MUST_BE_ARRAY]         = 'The email validation method must be passed an array.';
$translate[i18n_Error_Email::INVALID_ADDRESS]       = 'Invalid email address: %s';
$translate[i18n_Error_Email::ATTACHMENT_MISSING]    = 'Unable to locate the following email attachment: %s';
$translate[i18n_Error_Email::ATTACHMENT_UNREADABLE] = 'Unable to open this attachment: %s';
$translate[i18n_Error_Email::NO_RECIPIENTS]         = 'You must include recipients: To, Cc, or Bcc';
$translate[i18n_Error_Email::SEND_FAILURE_PHPMAIL]  = 'Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.';
$translate[i18n_Error_Email::SEND_FAILURE_SENDMAIL] = 'Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.';
$translate[i18n_Error_Email::SEND_FAILURE_SMTP]     = 'Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.';
$translate[i18n_Error_Email::SENT]                  = 'Your message has been successfully sent using the following protocol: %s';
$translate[i18n_Error_Email::NO_SOCKET]             = 'Unable to open a socket to Sendmail. Please check settings.';
$translate[i18n_Error_Email::NO_HOSTNAME]           = 'You did not specify a SMTP hostname.';
$translate[i18n_Error_Email::SMTP_ERROR]            = 'The following SMTP error was encountered: %s';
$translate[i18n_Error_Email::NO_SMTP_UNPW]          = 'Error: You must assign a SMTP username and password.';
$translate[i18n_Error_Email::FAILED_SMTP_LOGIN]     = 'Failed to send AUTH LOGIN command. Error: %s';
$translate[i18n_Error_Email::SMTP_AUTH_UN]          = 'Failed to authenticate username. Error: %s';
$translate[i18n_Error_Email::SMTP_AUTH_PW]          = 'Failed to authenticate password. Error: %s';
$translate[i18n_Error_Email::SMTP_DATA_FAILURE]     = 'Unable to send data: %s';
$translate[i18n_Error_Email::EXIT_STATUS]           = 'Exit status code: %s';