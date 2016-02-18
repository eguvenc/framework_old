--
-- Test SQL File for Tutorials
--

--
-- Database: `membership`
--

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS membership;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT null AUTO_INCREMENT,
  `user_username` varchar(160) NOT null,
  `user_password` varchar(40) NOT null,
  `user_email` varchar(160) NOT null,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;