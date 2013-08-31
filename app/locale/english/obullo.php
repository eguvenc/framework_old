<?php

/*
 * Obullo English Locale Items.
 */

// Odm
//-----------------------------------------------------------------
$lang['Data updated succesfully.']    = 'Data updated succesfully.';
$lang['Data not saved, please do some changes.'] = 'Data not saved, please do some changes.';
$lang['Data inserted succesfully.']   = 'Data inserted succesfully.';
$lang['Data insert error.']           = 'Data insert error.';
$lang['Data deleted succesfully.']    = 'Data deleted succesfully.';
$lang['Delete error or record already deleted.'] = 'Delete error or record already deleted.';
$lang['We couldn\'t save data at this time please try again. Error: '] = "'We couldn\'t save data at this time please try again. Error: '";
$lang['There are some errors in the form fields.'] = 'There are some errors in the form fields.';

// Validator
//-----------------------------------------------------------------
$lang['required']              = "The %s field is required.";
$lang['isset']                 = "The %s field must have a value.";
$lang['validEmail']            = "The %s field must contain a valid email address.";
$lang['validEmails']           = "The %s field must contain all valid email addresses.";
$lang['validEmailDns']         = "The %s field must contain all valid email addresses.";
$lang['validUrl']              = "The %s field must contain a valid URL.";
$lang['validIp']               = "The %s field must contain a valid IP.";
$lang['minLen']                = "The %s field must be at least %s characters in length.";
$lang['maxLen']                = "The %s field can not exceed %s characters in length.";
$lang['exactLen']              = "The %s field must be exactly %s characters in length.";
$lang['alpha']                 = "The %s field may only contain alphabetical characters.";
$lang['alphaNumeric']          = "The %s field may only contain alpha-numeric characters.";
$lang['alphaDash']             = "The %s field may only contain alpha-numeric characters, underscores, and dashes.";
$lang['numeric']               = "The %s field must contain only numbers.";
$lang['isNumeric']             = "The %s field must contain only numeric characters.";
$lang['integer']               = "The %s field must contain an integer.";
$lang['matches']               = "The %s field does not match the %s field.";
$lang['isNatural']             = "The %s field must contain only positive numbers.";
$lang['isNatural_No_Zero']     = "The %s field must contain a number greater than zero.";
$lang['noSpace']               = "The %s field can not contain space characters.";
$lang['validDate']             = "The %s field must contain a valid date.";

// Ftp
//-----------------------------------------------------------------
$lang['ftp_no_connection']           = "Unable to locate a valid connection ID.  Please make sure you are connected before peforming any file routines.";
$lang['ftp_unable_to_connect']       = "Unable to connect to your FTP server using the supplied hostname.";
$lang['ftp_unable_to_login']         = "Unable to login to your FTP server.  Please check your username and password.";
$lang['ftp_unable_to_makdir']        = "Unable to create the directory you have specified.";
$lang['ftp_unable_to_changedir']     = "Unable to change directories.";
$lang['ftp_unable_to_chmod']         = "Unable to set file permissions.  Please check your path.  Note: This feature is only available in PHP 5 or higher.";
$lang['ftp_unable_to_upload']        = "Unable to upload the specified file.  Please check your path.";
$lang['ftp_unable_to_download']      = "Unable to download the specified file.  Please check your path.";
$lang['ftp_no_source_file']          = "Unable to locate the source file.  Please check your path.";
$lang['ftp_unable_to_rename']        = "Unable to rename the file.";
$lang['ftp_unable_to_delete']        = "Unable to delete the file.";
$lang['ftp_unable_to_move']          = "Unable to move the file.  Please make sure the destination directory exists.";

// Email
//-----------------------------------------------------------------
$lang['email_must_be_array']         = "The email validation method must be passed an array.";
$lang['email_invalid_address']       = "Invalid email address: %s";
$lang['email_attachment_missing']    = "Unable to locate the following email attachment: %s";
$lang['email_attachment_unreadable'] = "Unable to open this attachment: %s";
$lang['email_no_recipients']         = "You must include recipients: To, Cc, or Bcc";
$lang['email_send_failure_phpmail']  = "Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.";
$lang['email_send_failure_sendmail'] = "Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.";
$lang['email_send_failure_smtp']     = "Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.";
$lang['email_sent']                  = "Your message has been successfully sent using the following protocol: %s";
$lang['email_no_socket']             = "Unable to open a socket to Sendmail. Please check settings.";
$lang['email_no_hostname']           = "You did not specify a SMTP hostname.";
$lang['email_smtp_error']            = "The following SMTP error was encountered: %s";
$lang['email_no_smtp_unpw']          = "Error: You must assign a SMTP username and password.";
$lang['email_failed_smtp_login']     = "Failed to send AUTH LOGIN command. Error: %s";
$lang['email_smtp_auth_un']          = "Failed to authenticate username. Error: %s";
$lang['email_smtp_auth_pw']          = "Failed to authenticate password. Error: %s";
$lang['email_smtp_data_failure']     = "Unable to send data: %s";
$lang['email_exit_status']           = "Exit status code: %s";

// Date
//-----------------------------------------------------------------
$lang['date_year']    = "Year";
$lang['date_years']   = "Years";
$lang['date_month']   = "Month";
$lang['date_months']  = "Months";
$lang['date_week']    = "Week";
$lang['date_weeks']   = "Weeks";
$lang['date_day']     = "Day";
$lang['date_days']    = "Days";
$lang['date_hour']    = "Hour";
$lang['date_hours']   = "Hours";
$lang['date_minute']  = "Minute";
$lang['date_minutes'] = "Minutes";
$lang['date_second']  = "Second";
$lang['date_seconds'] = "Seconds";

$lang['UM12']	= '(UTC -12:00) Baker/Howland Island';
$lang['UM11']	= '(UTC -11:00) Samoa Time Zone, Niue';
$lang['UM10']	= '(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti';
$lang['UM95']	= '(UTC -9:30) Marquesas Islands';
$lang['UM9']	= '(UTC -9:00) Alaska Standard Time, Gambier Islands';
$lang['UM8']	= '(UTC -8:00) Pacific Standard Time, Clipperton Island';
$lang['UM7']	= '(UTC -7:00) Mountain Standard Time';
$lang['UM6']	= '(UTC -6:00) Central Standard Time';
$lang['UM5']	= '(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time';
$lang['UM45']	= '(UTC -4:30) Venezuelan Standard Time';
$lang['UM4']	= '(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time';
$lang['UM35']	= '(UTC -3:30) Newfoundland Standard Time';
$lang['UM3']	= '(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay';
$lang['UM2']	= '(UTC -2:00) South Georgia/South Sandwich Islands';
$lang['UM1']	= '(UTC -1:00) Azores, Cape Verde Islands';
$lang['UTC']	= '(UTC) Greenwich Mean Time, Western European Time';
$lang['UP1']	= '(UTC +1:00) Central European Time, West Africa Time';
$lang['UP2']	= '(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time';
$lang['UP3']	= '(UTC +3:00) Moscow Time, East Africa Time';
$lang['UP35']	= '(UTC +3:30) Iran Standard Time';
$lang['UP4']	= '(UTC +4:00) Azerbaijan Standard Time, Samara Time';
$lang['UP45']	= '(UTC +4:30) Afghanistan';
$lang['UP5']	= '(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time';
$lang['UP55']	= '(UTC +5:30) Indian Standard Time, Sri Lanka Time';
$lang['UP575']	= '(UTC +5:45) Nepal Time';
$lang['UP6']	= '(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time';
$lang['UP65']	= '(UTC +6:30) Cocos Islands, Myanmar';
$lang['UP7']	= '(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam';
$lang['UP8']	= '(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time';
$lang['UP875']	= '(UTC +8:45) Australian Central Western Standard Time';
$lang['UP9']	= '(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time';
$lang['UP95']	= '(UTC +9:30) Australian Central Standard Time';
$lang['UP10']	= '(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time';
$lang['UP105']	= '(UTC +10:30) Lord Howe Island';
$lang['UP11']	= '(UTC +11:00) Magadan Time, Solomon Islands, Vanuatu';
$lang['UP115']	= '(UTC +11:30) Norfolk Island';
$lang['UP12']	= '(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time';
$lang['UP1275']	= '(UTC +12:45) Chatham Islands Standard Time';
$lang['UP13']	= '(UTC +13:00) Phoenix Islands Time, Tonga';
$lang['UP14']	= '(UTC +14:00) Line Islands';