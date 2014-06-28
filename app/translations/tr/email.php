<?php

// Email English
//-----------------------------------------------------------------

$translate['email_must_be_array']         = "The email validation method must be passed an array.";
$translate['email_invalid_address']       = "Invalid email address: %s";
$translate['email_attachment_missing']    = "Unable to locate the following email attachment: %s";
$translate['email_attachment_unreadable'] = "Unable to open this attachment: %s";
$translate['email_no_recipients']         = "You must include recipients: To, Cc, or Bcc";
$translate['email_send_failure_phpmail']  = "Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.";
$translate['email_send_failure_sendmail'] = "Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.";
$translate['email_send_failure_smtp']     = "Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.";
$translate['email_sent']                  = "Your message has been successfully sent using the following protocol: %s";
$translate['email_no_socket']             = "Unable to open a socket to Sendmail. Please check settings.";
$translate['email_no_hostname']           = "You did not specify a SMTP hostname.";
$translate['email_smtp_error']            = "The following SMTP error was encountered: %s";
$translate['email_no_smtp_unpw']          = "Error: You must assign a SMTP username and password.";
$translate['email_failed_smtp_login']     = "Failed to send AUTH LOGIN command. Error: %s";
$translate['email_smtp_auth_un']          = "Failed to authenticate username. Error: %s";
$translate['email_smtp_auth_pw']          = "Failed to authenticate password. Error: %s";
$translate['email_smtp_data_failure']     = "Unable to send data: %s";
$translate['email_exit_status']           = "Exit status code: %s";