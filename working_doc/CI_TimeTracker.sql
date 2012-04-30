SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";



-- ------------------
-- TimeTracker tables
-- ------------------

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `start_UNIX` datetime NOT NULL,
  `start_LOCAL` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `categorie_ID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_bin NOT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `user_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `l_activities_tags`
--
CREATE TABLE IF NOT EXISTS `l_activities_tags` (
  `activity_ID` int(10) unsigned NOT NULL,
  `tag_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`activity_ID`,`tag_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `l_categories_shared`
--
CREATE TABLE IF NOT EXISTS `l_categories_shared` (
  `categorie_ID` int(10) unsigned NOT NULL,
  `shared_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`categorie_ID`,`shared_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `l_isinvitedtoshare`
--
CREATE TABLE IF NOT EXISTS `l_isinvitedtoshare` (
  `user_ID` int(10) unsigned NOT NULL,
  `shared_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_ID`,`shared_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `shared`
--
CREATE TABLE IF NOT EXISTS `shared` (
  `ID` int(11) NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `tags`
--
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) COLLATE utf8_bin NOT NULL,
  `user_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `values`
--
CREATE TABLE IF NOT EXISTS `values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(200) COLLATE utf8_bin NOT NULL,
  `activity_ID` int(10) unsigned NOT NULL,
  `value_type_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `values_types`
--
CREATE TABLE IF NOT EXISTS `values_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `desc` text COLLATE utf8_bin NOT NULL,
  `type` varchar(100) COLLATE utf8_bin NOT NULL,
  `user_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;






-- -----------------
-- Tank Auth tables
-- -----------------

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
