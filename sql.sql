-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 09, 2022 at 03:37 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `gs_bans`
--

CREATE TABLE `gs_bans` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `expire` int UNSIGNED DEFAULT NULL,
  `ban_creator` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_categories`
--

CREATE TABLE `gs_categories` (
  `id` int UNSIGNED NOT NULL,
  `cat_name` varchar(80) NOT NULL DEFAULT 'New Category',
  `disp_position` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_categories`
--

INSERT INTO `gs_categories` (`id`, `cat_name`, `disp_position`) VALUES
(2, 'General', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gs_censoring`
--

CREATE TABLE `gs_censoring` (
  `id` int UNSIGNED NOT NULL,
  `search_for` varchar(60) NOT NULL DEFAULT '',
  `replace_with` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_codes`
--

CREATE TABLE `gs_codes` (
  `id` int NOT NULL,
  `code` varchar(48) CHARACTER SET latin1 DEFAULT NULL,
  `by` int DEFAULT NULL,
  `used` int NOT NULL DEFAULT '0',
  `admin` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `usedat` datetime DEFAULT NULL,
  `beta` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_config`
--

CREATE TABLE `gs_config` (
  `conf_name` varchar(255) NOT NULL DEFAULT '',
  `conf_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_config`
--

INSERT INTO `gs_config` (`conf_name`, `conf_value`) VALUES
('o_additional_navlinks', ''),
('o_admin_email', 'admin@website.com'),
('o_announcement', '0'),
('o_announcement_message', 'Enter your announcement here.'),
('o_avatars', '1'),
('o_avatars_dir', 'img/avatars'),
('o_avatars_height', '120'),
('o_avatars_size', '10240'),
('o_avatars_width', '120'),
('o_base_url', 'http://website.com/forums'),
('o_board_desc', 'Get good. Get GameSense.'),
('o_board_title', 'GameSense'),
('o_censoring', '0'),
('o_crypto_pas', '66iZa4SHFXcxyI5W9vJ9U8fK3'),
('o_cur_version', '1.5.11'),
('o_database_revision', '21'),
('o_date_format', 'm-d-Y'),
('o_default_dst', '0'),
('o_default_email_setting', '2'),
('o_default_lang', 'English'),
('o_default_style', 'Cobalt.min'),
('o_default_timezone', '-5'),
('o_default_user_group', '4'),
('o_disp_posts_default', '25'),
('o_disp_topics_default', '30'),
('o_feed_ttl', '0'),
('o_feed_type', '2'),
('o_forum_subscriptions', '1'),
('o_gzip', '0'),
('o_indent_num_spaces', '4'),
('o_mailing_list', 'admin@website.com'),
('o_maintenance', '0'),
('o_maintenance_message', 'The forums are temporarily down for maintenance. Please try again in a few minutes.'),
('o_make_links', '1'),
('o_parser_revision', '2'),
('o_pms_enabled', '1'),
('o_pms_flasher', '1'),
('o_pms_min_kolvo', '0'),
('o_quickjump', '1'),
('o_quickpost', '1'),
('o_quote_depth', '3'),
('o_redirect_delay', '1'),
('o_regs_allow', '1'),
('o_regs_report', '0'),
('o_regs_verify', '0'),
('o_report_method', '0'),
('o_rules', '0'),
('o_rules_message', 'Enter your rules here'),
('o_searchindex_revision', '2'),
('o_search_all_forums', '1'),
('o_show_dot', '0'),
('o_show_post_count', '1'),
('o_show_user_info', '1'),
('o_show_version', '0'),
('o_signatures', '1'),
('o_smilies', '1'),
('o_smilies_sig', '1'),
('o_smtp_host', NULL),
('o_smtp_pass', NULL),
('o_smtp_ssl', '0'),
('o_smtp_user', NULL),
('o_timeout_online', '300'),
('o_timeout_visit', '1800'),
('o_time_format', 'H:i:s'),
('o_topic_review', '15'),
('o_topic_subscriptions', '1'),
('o_topic_views', '0'),
('o_users_online', '1'),
('o_webmaster_email', 'admin@website.com'),
('p_allow_banned_email', '1'),
('p_allow_dupe_email', '0'),
('p_force_guest_email', '1'),
('p_message_all_caps', '1'),
('p_message_bbcode', '1'),
('p_message_img_tag', '1'),
('p_sig_all_caps', '1'),
('p_sig_bbcode', '1'),
('p_sig_img_tag', '0'),
('p_sig_length', '400'),
('p_sig_lines', '4'),
('p_subject_all_caps', '1'),
('recaptcha_enabled', '1'),
('recaptcha_location_guestpost', '0'),
('recaptcha_location_login', '1'),
('recaptcha_location_register', '1'),
('recaptcha_secret_key', 'secret key'),
('recaptcha_site_key', 'site key');

-- --------------------------------------------------------

--
-- Table structure for table `gs_forums`
--

CREATE TABLE `gs_forums` (
  `id` int UNSIGNED NOT NULL,
  `forum_name` varchar(80) NOT NULL DEFAULT 'New forum',
  `forum_desc` text,
  `redirect_url` varchar(100) DEFAULT NULL,
  `moderators` text,
  `num_topics` mediumint UNSIGNED NOT NULL DEFAULT '0',
  `num_posts` mediumint UNSIGNED NOT NULL DEFAULT '0',
  `last_post` int UNSIGNED DEFAULT NULL,
  `last_post_id` int UNSIGNED DEFAULT NULL,
  `last_poster` varchar(200) DEFAULT NULL,
  `last_topic` varchar(255) DEFAULT NULL,
  `sort_by` tinyint(1) NOT NULL DEFAULT '0',
  `disp_position` int NOT NULL DEFAULT '0',
  `cat_id` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_forums`
--

INSERT INTO `gs_forums` (`id`, `forum_name`, `forum_desc`, `redirect_url`, `moderators`, `num_topics`, `num_posts`, `last_post`, `last_post_id`, `last_poster`, `last_topic`, `sort_by`, `disp_position`, `cat_id`) VALUES
(2, 'Announcements', 'Stay up to date with the latest news', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, 0, 2),
(3, 'General talk', 'Talk about anything', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, 1, 2),
(4, 'Spotlight', 'Show off screenshots or videos', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `gs_forum_perms`
--

CREATE TABLE `gs_forum_perms` (
  `group_id` int NOT NULL DEFAULT '0',
  `forum_id` int NOT NULL DEFAULT '0',
  `read_forum` tinyint(1) NOT NULL DEFAULT '1',
  `post_replies` tinyint(1) NOT NULL DEFAULT '1',
  `post_topics` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_forum_subscriptions`
--

CREATE TABLE `gs_forum_subscriptions` (
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `forum_id` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_groups`
--

CREATE TABLE `gs_groups` (
  `g_id` int UNSIGNED NOT NULL,
  `g_title` varchar(50) NOT NULL DEFAULT '',
  `g_user_title` varchar(50) DEFAULT NULL,
  `g_promote_min_posts` int UNSIGNED NOT NULL DEFAULT '0',
  `g_promote_next_group` int UNSIGNED NOT NULL DEFAULT '0',
  `g_moderator` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_edit_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_rename_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_change_passwords` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_ban_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_promote_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_read_board` tinyint(1) NOT NULL DEFAULT '1',
  `g_view_users` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_replies` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_topics` tinyint(1) NOT NULL DEFAULT '1',
  `g_edit_posts` tinyint(1) NOT NULL DEFAULT '1',
  `g_delete_posts` tinyint(1) NOT NULL DEFAULT '1',
  `g_delete_topics` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_links` tinyint(1) NOT NULL DEFAULT '1',
  `g_set_title` tinyint(1) NOT NULL DEFAULT '1',
  `g_search` tinyint(1) NOT NULL DEFAULT '1',
  `g_search_users` tinyint(1) NOT NULL DEFAULT '1',
  `g_send_email` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_flood` smallint NOT NULL DEFAULT '30',
  `g_search_flood` smallint NOT NULL DEFAULT '30',
  `g_email_flood` smallint NOT NULL DEFAULT '60',
  `g_report_flood` smallint NOT NULL DEFAULT '60',
  `g_color` varchar(15) DEFAULT NULL,
  `g_pm` tinyint(1) NOT NULL DEFAULT '1',
  `g_pm_limit` int UNSIGNED NOT NULL DEFAULT '100',
  `g_color2` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_groups`
--

INSERT INTO `gs_groups` (`g_id`, `g_title`, `g_user_title`, `g_promote_min_posts`, `g_promote_next_group`, `g_moderator`, `g_mod_edit_users`, `g_mod_rename_users`, `g_mod_change_passwords`, `g_mod_ban_users`, `g_mod_promote_users`, `g_read_board`, `g_view_users`, `g_post_replies`, `g_post_topics`, `g_edit_posts`, `g_delete_posts`, `g_delete_topics`, `g_post_links`, `g_set_title`, `g_search`, `g_search_users`, `g_send_email`, `g_post_flood`, `g_search_flood`, `g_email_flood`, `g_report_flood`, `g_color`, `g_pm`, `g_pm_limit`, `g_color2`) VALUES
(1, 'Administrators', 'Administrator', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, '#B4E61E', 1, 0, '#B4E61E'),
(2, 'Moderators', 'Moderator', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, '#FECC01', 1, 100, '#FECC01'),
(3, 'Guests', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 60, 30, 0, 0, NULL, 1, 100, NULL),
(4, 'Members', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 0, 0, 1, 0, 0, 0, 1, 60, 30, 60, 60, '', 1, 100, '#D4D4D3'),
(5, 'Premium', 'Premium', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 60, 30, 60, 60, '#FF0000', 1, 100, '#FF0000');

-- --------------------------------------------------------

--
-- Table structure for table `gs_online`
--

CREATE TABLE `gs_online` (
  `user_id` int UNSIGNED NOT NULL DEFAULT '1',
  `ident` varchar(200) NOT NULL DEFAULT '',
  `logged` int UNSIGNED NOT NULL DEFAULT '0',
  `idle` tinyint(1) NOT NULL DEFAULT '0',
  `last_post` int UNSIGNED DEFAULT NULL,
  `last_search` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_online`
--

INSERT INTO `gs_online` (`user_id`, `ident`, `logged`, `idle`, `last_post`, `last_search`) VALUES
(2, 'admin', 1665286643, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gs_pms_new_block`
--

CREATE TABLE `gs_pms_new_block` (
  `bl_id` int UNSIGNED NOT NULL DEFAULT '0',
  `bl_user_id` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_pms_new_posts`
--

CREATE TABLE `gs_pms_new_posts` (
  `id` int UNSIGNED NOT NULL,
  `poster` varchar(200) NOT NULL DEFAULT '',
  `poster_id` int UNSIGNED NOT NULL DEFAULT '1',
  `poster_ip` varchar(39) DEFAULT NULL,
  `message` text,
  `hide_smilies` tinyint(1) NOT NULL DEFAULT '0',
  `posted` int UNSIGNED NOT NULL DEFAULT '0',
  `edited` int UNSIGNED DEFAULT NULL,
  `edited_by` varchar(200) DEFAULT NULL,
  `post_new` tinyint(1) NOT NULL DEFAULT '1',
  `topic_id` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_pms_new_topics`
--

CREATE TABLE `gs_pms_new_topics` (
  `id` int UNSIGNED NOT NULL,
  `topic` varchar(255) NOT NULL DEFAULT '',
  `starter` varchar(200) NOT NULL DEFAULT '',
  `starter_id` int UNSIGNED NOT NULL DEFAULT '0',
  `to_user` varchar(200) NOT NULL DEFAULT '',
  `to_id` int UNSIGNED NOT NULL DEFAULT '0',
  `replies` mediumint UNSIGNED NOT NULL DEFAULT '0',
  `last_posted` int UNSIGNED NOT NULL DEFAULT '0',
  `last_poster` tinyint(1) NOT NULL DEFAULT '0',
  `see_st` int UNSIGNED NOT NULL DEFAULT '0',
  `see_to` int UNSIGNED NOT NULL DEFAULT '0',
  `topic_st` tinyint NOT NULL DEFAULT '0',
  `topic_to` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_posts`
--

CREATE TABLE `gs_posts` (
  `id` int UNSIGNED NOT NULL,
  `poster` varchar(200) NOT NULL DEFAULT '',
  `poster_id` int UNSIGNED NOT NULL DEFAULT '1',
  `poster_ip` varchar(39) DEFAULT NULL,
  `poster_email` varchar(80) DEFAULT NULL,
  `message` mediumtext,
  `hide_smilies` tinyint(1) NOT NULL DEFAULT '0',
  `posted` int UNSIGNED NOT NULL DEFAULT '0',
  `edited` int UNSIGNED DEFAULT NULL,
  `edited_by` varchar(200) DEFAULT NULL,
  `topic_id` int UNSIGNED NOT NULL DEFAULT '0',
  `likes` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_reports`
--

CREATE TABLE `gs_reports` (
  `id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL DEFAULT '0',
  `topic_id` int UNSIGNED NOT NULL DEFAULT '0',
  `forum_id` int UNSIGNED NOT NULL DEFAULT '0',
  `reported_by` int UNSIGNED NOT NULL DEFAULT '0',
  `created` int UNSIGNED NOT NULL DEFAULT '0',
  `message` text,
  `zapped` int UNSIGNED DEFAULT NULL,
  `zapped_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_search_cache`
--

CREATE TABLE `gs_search_cache` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `ident` varchar(200) NOT NULL DEFAULT '',
  `search_data` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_search_cache`
--

INSERT INTO `gs_search_cache` (`id`, `ident`, `search_data`) VALUES
(655742132, 'premium', 'a:6:{s:10:\"search_ids\";s:30:\"a:2:{i:0;s:1:\"8\";i:1;s:1:\"3\";}\";s:8:\"num_hits\";i:2;s:7:\"sort_by\";i:0;s:8:\"sort_dir\";s:4:\"DESC\";s:7:\"show_as\";s:6:\"topics\";s:11:\"search_type\";a:3:{i:0;s:6:\"action\";i:1;s:16:\"show_user_topics\";i:2;i:2;}}');

-- --------------------------------------------------------

--
-- Table structure for table `gs_search_matches`
--

CREATE TABLE `gs_search_matches` (
  `post_id` int UNSIGNED NOT NULL DEFAULT '0',
  `word_id` int UNSIGNED NOT NULL DEFAULT '0',
  `subject_match` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_search_words`
--

CREATE TABLE `gs_search_words` (
  `id` int UNSIGNED NOT NULL,
  `word` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_search_words`
--

INSERT INTO `gs_search_words` (`id`, `word`) VALUES
(1, 'looking'),
(2, 'guess'),
(3, 'install'),
(4, 'fluxbb'),
(5, 'appears'),
(6, 'worked'),
(7, 'log'),
(8, 'head'),
(9, 'administration'),
(10, 'control'),
(11, 'panel'),
(12, 'configure'),
(13, 'forum'),
(18, 'niggers'),
(19, 'nigger'),
(20, 'media'),
(21, 'eee'),
(27, 'body'),
(28, 'style'),
(29, 'background-image'),
(30, 'url'),
(31, 'imgur'),
(32, 'a1xpzvc'),
(33, 'png'),
(34, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `gs_topics`
--

CREATE TABLE `gs_topics` (
  `id` int UNSIGNED NOT NULL,
  `poster` varchar(200) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `posted` int UNSIGNED NOT NULL DEFAULT '0',
  `first_post_id` int UNSIGNED NOT NULL DEFAULT '0',
  `last_post` int UNSIGNED NOT NULL DEFAULT '0',
  `last_post_id` int UNSIGNED NOT NULL DEFAULT '0',
  `last_poster` varchar(200) DEFAULT NULL,
  `num_views` mediumint UNSIGNED NOT NULL DEFAULT '0',
  `num_replies` mediumint UNSIGNED NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `moved_to` int UNSIGNED DEFAULT NULL,
  `forum_id` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_topic_subscriptions`
--

CREATE TABLE `gs_topic_subscriptions` (
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `topic_id` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gs_updates`
--

CREATE TABLE `gs_updates` (
  `id` int NOT NULL,
  `game` varchar(255) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gs_updates`
--

INSERT INTO `gs_updates` (`id`, `game`, `updated`) VALUES
(1, 'csgo', '2022-04-26 15:13:06'),
(2, 'csgo_beta', '2022-04-26 09:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `gs_users`
--

CREATE TABLE `gs_users` (
  `id` int UNSIGNED NOT NULL,
  `group_id` int UNSIGNED NOT NULL DEFAULT '3',
  `username` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(50) DEFAULT NULL,
  `by` int DEFAULT NULL,
  `csgo` datetime DEFAULT NULL,
  `realname` varchar(40) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `jabber` varchar(80) DEFAULT NULL,
  `icq` varchar(12) DEFAULT NULL,
  `msn` varchar(80) DEFAULT NULL,
  `aim` varchar(30) DEFAULT NULL,
  `yahoo` varchar(30) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL,
  `signature` text,
  `disp_topics` tinyint UNSIGNED DEFAULT NULL,
  `disp_posts` tinyint UNSIGNED DEFAULT NULL,
  `email_setting` tinyint(1) NOT NULL DEFAULT '1',
  `notify_with_post` tinyint(1) NOT NULL DEFAULT '0',
  `auto_notify` tinyint(1) NOT NULL DEFAULT '0',
  `show_smilies` tinyint(1) NOT NULL DEFAULT '1',
  `show_img` tinyint(1) NOT NULL DEFAULT '1',
  `show_img_sig` tinyint(1) NOT NULL DEFAULT '1',
  `show_avatars` tinyint(1) NOT NULL DEFAULT '1',
  `show_sig` tinyint(1) NOT NULL DEFAULT '1',
  `timezone` float NOT NULL DEFAULT '0',
  `dst` tinyint(1) NOT NULL DEFAULT '0',
  `time_format` tinyint(1) NOT NULL DEFAULT '0',
  `date_format` tinyint(1) NOT NULL DEFAULT '0',
  `language` varchar(25) NOT NULL DEFAULT 'English',
  `style` varchar(25) NOT NULL DEFAULT 'Cobalt.min',
  `num_posts` int UNSIGNED NOT NULL DEFAULT '0',
  `last_post` int UNSIGNED DEFAULT NULL,
  `last_search` int UNSIGNED DEFAULT NULL,
  `last_email_sent` int UNSIGNED DEFAULT NULL,
  `last_report_sent` int UNSIGNED DEFAULT NULL,
  `registered` int UNSIGNED NOT NULL DEFAULT '0',
  `registration_ip` varchar(39) NOT NULL DEFAULT '0.0.0.0',
  `last_visit` int UNSIGNED NOT NULL DEFAULT '0',
  `admin_note` varchar(30) DEFAULT NULL,
  `activate_string` varchar(80) DEFAULT NULL,
  `activate_key` varchar(8) DEFAULT NULL,
  `messages_enable` tinyint(1) NOT NULL DEFAULT '1',
  `messages_email` tinyint(1) NOT NULL DEFAULT '0',
  `messages_flag` tinyint(1) NOT NULL DEFAULT '0',
  `messages_new` int UNSIGNED NOT NULL DEFAULT '0',
  `messages_all` int UNSIGNED NOT NULL DEFAULT '0',
  `pmsn_last_post` int UNSIGNED DEFAULT NULL,
  `beta` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `img_key` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `ga` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `ga_enabled` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0',
  `parts` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `newparts` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `hwid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `hwid_new` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `hwid_ip` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `hwid_ip_new` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `hwid_reason` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `discord` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `discord_new` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `discord_ip` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `discord_ip_new` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `discord_reason` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `used_version` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gs_users`
--

INSERT INTO `gs_users` (`id`, `group_id`, `username`, `password`, `email`, `title`, `by`, `csgo`, `realname`, `url`, `jabber`, `icq`, `msn`, `aim`, `yahoo`, `location`, `signature`, `disp_topics`, `disp_posts`, `email_setting`, `notify_with_post`, `auto_notify`, `show_smilies`, `show_img`, `show_img_sig`, `show_avatars`, `show_sig`, `timezone`, `dst`, `time_format`, `date_format`, `language`, `style`, `num_posts`, `last_post`, `last_search`, `last_email_sent`, `last_report_sent`, `registered`, `registration_ip`, `last_visit`, `admin_note`, `activate_string`, `activate_key`, `messages_enable`, `messages_email`, `messages_flag`, `messages_new`, `messages_all`, `pmsn_last_post`, `beta`, `img_key`, `ga`, `ga_enabled`, `parts`, `newparts`, `hwid`, `hwid_new`, `hwid_ip`, `hwid_ip_new`, `hwid_reason`, `discord`, `discord_new`, `discord_ip`, `discord_ip_new`, `discord_reason`, `used_version`) VALUES
(1, 3, 'Guest', 'Guest', 'Guest', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 'English', 'Cobalt.min', 0, NULL, NULL, NULL, NULL, 0, '0.0.0.0', 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '0', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'admin', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'admin@website.com', NULL, NULL, '2023-12-23 07:15:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, -6, 0, 0, 0, 'English', 'Cobalt.min', 15, 1664775471, NULL, NULL, NULL, 1660902341, '108.162.216.41', 1665286629, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1661250566, NULL, NULL, NULL, '0', 'HWID: 0\n', NULL, '0', NULL, NULL, '209.58.159.99', NULL, NULL, NULL, NULL, NULL, NULL, '7');

-- --------------------------------------------------------

--
-- Table structure for table `gs_versions`
--

CREATE TABLE `gs_versions` (
  `id` int NOT NULL,
  `name` text,
  `version` decimal(10,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gs_versions`
--

INSERT INTO `gs_versions` (`id`, `name`, `version`) VALUES
(1, 'loader', '7.0'),
(2, 'csgo', '0.2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gs_bans`
--
ALTER TABLE `gs_bans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_bans_username_idx` (`username`(25));

--
-- Indexes for table `gs_categories`
--
ALTER TABLE `gs_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gs_censoring`
--
ALTER TABLE `gs_censoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gs_codes`
--
ALTER TABLE `gs_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `gs_config`
--
ALTER TABLE `gs_config`
  ADD PRIMARY KEY (`conf_name`);

--
-- Indexes for table `gs_forums`
--
ALTER TABLE `gs_forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gs_forum_perms`
--
ALTER TABLE `gs_forum_perms`
  ADD PRIMARY KEY (`group_id`,`forum_id`);

--
-- Indexes for table `gs_forum_subscriptions`
--
ALTER TABLE `gs_forum_subscriptions`
  ADD PRIMARY KEY (`user_id`,`forum_id`);

--
-- Indexes for table `gs_groups`
--
ALTER TABLE `gs_groups`
  ADD PRIMARY KEY (`g_id`);

--
-- Indexes for table `gs_online`
--
ALTER TABLE `gs_online`
  ADD UNIQUE KEY `gs_online_user_id_ident_idx` (`user_id`,`ident`(25)),
  ADD KEY `gs_online_ident_idx` (`ident`(25)),
  ADD KEY `gs_online_logged_idx` (`logged`);

--
-- Indexes for table `gs_pms_new_block`
--
ALTER TABLE `gs_pms_new_block`
  ADD KEY `gs_pms_new_block_bl_id_idx` (`bl_id`),
  ADD KEY `gs_pms_new_block_bl_user_id_idx` (`bl_user_id`);

--
-- Indexes for table `gs_pms_new_posts`
--
ALTER TABLE `gs_pms_new_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_pms_new_posts_topic_id_idx` (`topic_id`),
  ADD KEY `gs_pms_new_posts_multi_idx` (`poster_id`,`topic_id`);

--
-- Indexes for table `gs_pms_new_topics`
--
ALTER TABLE `gs_pms_new_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_pms_new_topics_multi_idx_st` (`starter_id`,`topic_st`),
  ADD KEY `gs_pms_new_topics_multi_idx_to` (`to_id`,`topic_to`);

--
-- Indexes for table `gs_posts`
--
ALTER TABLE `gs_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_posts_topic_id_idx` (`topic_id`),
  ADD KEY `gs_posts_multi_idx` (`poster_id`,`topic_id`);

--
-- Indexes for table `gs_reports`
--
ALTER TABLE `gs_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_reports_zapped_idx` (`zapped`);

--
-- Indexes for table `gs_search_cache`
--
ALTER TABLE `gs_search_cache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_search_cache_ident_idx` (`ident`(8));

--
-- Indexes for table `gs_search_matches`
--
ALTER TABLE `gs_search_matches`
  ADD KEY `gs_search_matches_word_id_idx` (`word_id`),
  ADD KEY `gs_search_matches_post_id_idx` (`post_id`);

--
-- Indexes for table `gs_search_words`
--
ALTER TABLE `gs_search_words`
  ADD PRIMARY KEY (`word`),
  ADD KEY `gs_search_words_id_idx` (`id`);

--
-- Indexes for table `gs_topics`
--
ALTER TABLE `gs_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gs_topics_forum_id_idx` (`forum_id`),
  ADD KEY `gs_topics_moved_to_idx` (`moved_to`),
  ADD KEY `gs_topics_last_post_idx` (`last_post`),
  ADD KEY `gs_topics_first_post_id_idx` (`first_post_id`);

--
-- Indexes for table `gs_topic_subscriptions`
--
ALTER TABLE `gs_topic_subscriptions`
  ADD PRIMARY KEY (`user_id`,`topic_id`);

--
-- Indexes for table `gs_updates`
--
ALTER TABLE `gs_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gs_users`
--
ALTER TABLE `gs_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gs_users_username_idx` (`username`(25)),
  ADD KEY `gs_users_registered_idx` (`registered`);

--
-- Indexes for table `gs_versions`
--
ALTER TABLE `gs_versions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gs_bans`
--
ALTER TABLE `gs_bans`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `gs_categories`
--
ALTER TABLE `gs_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gs_censoring`
--
ALTER TABLE `gs_censoring`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gs_codes`
--
ALTER TABLE `gs_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gs_forums`
--
ALTER TABLE `gs_forums`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gs_groups`
--
ALTER TABLE `gs_groups`
  MODIFY `g_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gs_pms_new_posts`
--
ALTER TABLE `gs_pms_new_posts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gs_pms_new_topics`
--
ALTER TABLE `gs_pms_new_topics`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gs_posts`
--
ALTER TABLE `gs_posts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `gs_reports`
--
ALTER TABLE `gs_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gs_search_words`
--
ALTER TABLE `gs_search_words`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `gs_topics`
--
ALTER TABLE `gs_topics`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gs_updates`
--
ALTER TABLE `gs_updates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gs_users`
--
ALTER TABLE `gs_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `gs_versions`
--
ALTER TABLE `gs_versions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
