--
-- Test Module SQL File for Validation Model
--

--
-- Database: `obullo`
--

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS obullo;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `usr_id` int(11) NOT null AUTO_INCREMENT,
  `usr_username` varchar(160) NOT null,
  `usr_password` varchar(40) NOT null,
  `usr_email` varchar(160) NOT null,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `captcha`
--

CREATE TABLE IF NOT EXISTS `ob_captcha` (
captcha_id bigint(13) unsigned NOT null auto_increment,
captcha_time int(10) unsigned NOT null,
ip_address varchar(16) default '0' NOT null,
word varchar(20) NOT null,
PRIMARY KEY `captcha_id` (`captcha_id`),
KEY `word` (`word`)
);

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS  `ob_sessions` (
session_id varchar(40) DEFAULT '0' NOT null,
ip_address varchar(16) DEFAULT '0' NOT null,
user_agent varchar(50) NOT null,
last_activity int(10) unsigned DEFAULT 0 NOT null,
user_data text character set utf8 NOT null,
PRIMARY KEY (session_id)
);