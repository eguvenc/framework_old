
--
-- Database: `demo_blog`
--

CREATE DATABASE IF NOT EXISTS `demo_blog`;
USE `demo_blog`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post_id` int(11) NOT NULL,
  `comment_name` varchar(50) NOT NULL,
  `comment_email` varchar(255) DEFAULT NULL,
  `comment_website` varchar(255) DEFAULT NULL,
  `comment_body` text NOT NULL,
  `comment_creation_date` datetime DEFAULT NULL,
  `comment_modification_date` datetime DEFAULT NULL,
  `comment_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `comment_post_id` (`comment_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(50) NOT NULL,
  `contact_email` varchar(50) NOT NULL,
  `contact_subject` varchar(255) NOT NULL,
  `contact_body` text,
  `contact_creation_date` datetime NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_user_id` int(11) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_content` text NOT NULL,
  `post_tags` varchar(255) DEFAULT NULL,
  `post_status` enum('Draft','Published','Archived') NOT NULL DEFAULT 'Published',
  `post_creation_date` datetime DEFAULT NULL,
  `post_modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `post_user_id` (`post_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(60) NOT NULL,
  `user_password` varchar(75) NOT NULL,
  `user_creation_date` datetime DEFAULT NULL,
  `user_modification_date` datetime DEFAULT NULL,
  `user_username` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`comment_post_id`) REFERENCES `posts` (`post_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_user_id`) REFERENCES `users` (`user_id`);