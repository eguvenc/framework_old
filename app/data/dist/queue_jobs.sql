
--
-- Database: `queue_jobs`
--

CREATE DATABASE IF NOT EXISTS `queue_jobs`;
USE `queue_jobs`;

-- --------------------------------------------------------

--
-- Table structure for table `failures`
--

CREATE TABLE IF NOT EXISTS `failures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `job_name` varchar(40) NOT NULL,
  `job_body` text NOT NULL,
  `job_repeats` int(11) NOT NULL DEFAULT '0',
  `job_failure_date` int(11) NOT NULL COMMENT 'unix timestamp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Failed Jobs' AUTO_INCREMENT=1 ;
