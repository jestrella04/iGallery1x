-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-10-2010 a las 16:20:45
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `ravennuke`
--

--
-- Estructura de tabla para la tabla `nuke_igallery_albums`
--

DROP TABLE IF EXISTS `nuke_igallery_albums`;
CREATE TABLE IF NOT EXISTS `nuke_igallery_albums` (
  `album_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `album_left` int(10) NOT NULL,
  `album_right` int(10) NOT NULL,
  `album_active` int(1) NOT NULL,
  `album_title` varchar(255) NOT NULL,
  `album_desc` text NOT NULL,
  `album_folder` varchar(255) NOT NULL,
  `album_cover` varchar(255) NOT NULL,
  `album_date` int(11) NOT NULL,
  PRIMARY KEY (`album_id`),
  KEY `album_id` (`album_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Volcar la base de datos para la tabla `nuke_igallery_albums`
--

INSERT INTO `nuke_igallery_albums` (`album_id`, `album_left`, `album_right`, `album_active`, `album_title`, `album_desc`, `album_folder`, `album_cover`, `album_date`) VALUES
(1, 1, 12, 1, 'Root', 'This is the root album, please don''t remove it from database. Be aware that your main categories must be child of Root album.', '', '', 0),
(2, 2, 3, 1, 'Cute Ladies', 'Some of the most beautiful ladies from all around the world.', 'Cute_Ladies', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nuke_igallery_comments`
--

DROP TABLE IF EXISTS `nuke_igallery_comments`;
CREATE TABLE IF NOT EXISTS `nuke_igallery_comments` (
  `comment_id` int(10) NOT NULL AUTO_INCREMENT,
  `comment_userid` int(10) NOT NULL,
  `comment_pictureid` int(10) NOT NULL,
  `comment_data` text NOT NULL,
  `comment_date` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

--
-- Volcar la base de datos para la tabla `nuke_igallery_comments`
--

INSERT INTO `nuke_igallery_comments` (`comment_id`, `comment_userid`, `comment_pictureid`, `comment_data`, `comment_date`) VALUES
(58, 2, 10, 'tremendisimo 2', 0),
(59, 2, 3, '123 probando', 0),
(60, 2, 3, '123 probando', 0),
(61, 2, 3, 'Yet another test comment', 1276977753),
(62, 2, 3, 'Yet another one...', 1276980959),
(63, 2, 8, 'cutest russian girl... for sure!!!', 1276981878),
(64, 2, 10, 'ok ok ok', 1276985811),
(65, 2, 3, 'Sed ultrices accumsan velit vel porta. Vestibulum eget nunc sem, eget cursus odio. Nullam eu sem vitae leo blandit pretium eget pulvinar odio. Mauris feugiat ante non libero aliquam egestas. Sed et dolor augue. Mauris faucibus nulla at ligula feugiat vel consequat ante pulvinar. In dolor elit, molestie eget faucibus at, convallis in nibh. la creta tu ere loco mano e????', 1277002156);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nuke_igallery_pictures`
--

DROP TABLE IF EXISTS `nuke_igallery_pictures`;
CREATE TABLE IF NOT EXISTS `nuke_igallery_pictures` (
  `picture_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `album_id` bigint(10) NOT NULL,
  `picture_title` varchar(80) NOT NULL,
  `picture_desc` varchar(255) NOT NULL,
  `picture_file` varchar(255) NOT NULL,
  `picture_type` tinyint(1) NOT NULL,
  `picture_counter` int(10) NOT NULL,
  `picture_date` int(11) NOT NULL,
  `picture_userid` int(10) NOT NULL,
  `picture_rating` int(10) NOT NULL DEFAULT '0',
  `picture_votes` int(10) NOT NULL DEFAULT '0',
  `picture_lastrate` int(11) NOT NULL,
  PRIMARY KEY (`picture_id`),
  UNIQUE KEY `picture_id` (`picture_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=131 ;

--
-- Volcar la base de datos para la tabla `nuke_igallery_pictures`
--

INSERT INTO `nuke_igallery_pictures` (`picture_id`, `album_id`, `picture_title`, `picture_desc`, `picture_file`, `picture_type`, `picture_counter`, `picture_date`, `picture_userid`, `picture_rating`, `picture_votes`, `picture_lastrate`) VALUES
(1, 2, 'Odalys Garcia', 'A Cuban actress, model, singer and show host.', 'odalys_garcia.jpg', 0, 48, 10, 0, 0, 0, 0),
(2, 2, 'Eva Mendes', 'An American actress of cuban parents', 'eva_mendes.jpg', 0, 52, 10, 0, 0, 0, 0),
(3, 2, 'Adriana Lima', 'A Brazilian supermodel best known as a Victoria''s Secret Angel', 'adriana_lima.jpg', 0, 185, 10, 0, 20, 4, 2010),
(4, 2, 'Vida Guerra', 'A cuban singer, actress and model', 'vida_guerra.jpg', 0, 37, 10, 0, 0, 0, 0),
(5, 2, 'Zoe Saldana', 'An american actress of dominican parents', 'zoe_saldana.jpg', 0, 42, 10, 0, 0, 0, 0),
(6, 2, 'Carmen Elektra', 'An american glamour model, actress, television personality, singer, dancer and sex symbol', 'carmen_electra.jpg', 0, 41, 10, 0, 0, 0, 0),
(7, 2, 'Michelle Rodriguez', 'An american actress of dominican and puertorican parents', 'michelle_rodriguez.jpg', 0, 35, 10, 0, 0, 0, 0),
(8, 2, 'Anna Kournikova', 'A retired russian professional tennis player and model', 'anna_kournikova.jpg', 0, 66, 10, 0, 11, 3, 2010),
(9, 2, 'Martha Heredia', 'Dominican singer. Winner of 2009''s Latin American Idol contest', 'martha_heredia.jpg', 0, 39, 10, 0, 4, 1, 0),
(10, 2, 'Amelia Vega', 'Dominican model and singer. Winner of 2003''s Miss Universo contest', 'amelia_vega.jpg', 0, 53, 10, 0, 10, 2, 0),
(21, 2, 'catherine_zeta_jones.jpg', '', 'catherine_zeta_jones.jpg', 0, 36, 19, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nuke_igallery_settings`
--

DROP TABLE IF EXISTS `nuke_igallery_settings`;
CREATE TABLE IF NOT EXISTS `nuke_igallery_settings` (
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  UNIQUE KEY `setting_name` (`setting_name`),
  KEY `setting_name_2` (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `nuke_igallery_settings`
--

INSERT INTO `nuke_igallery_settings` (`setting_name`, `setting_value`) VALUES
('igallery_title', 'iGallery: Simple yet Powerful gallery for RavenNuke(tm)'),
('pictures_path', 'Gallery/Pictures/'),
('thumbs_path', 'Gallery/Thumbs/'),
('show_rows', '5'),
('show_columns', '4'),
('show_details', '0'),
('upload_resize', '1'),
('upload_autosize', '720'),
('max_quality', '90'),
('thumbs_autosize', '150'),
('thumbs_quality', '75'),
('thumbs_format', 'JPG'),
('comments_allow', '1'),
('comments_public', '1'),
('tooltip_theme', 'Negroide'),
('pop_count', '10'),
('ext_allow', 'png,jpeg,jpg,jpe,gif'),
('upload_path', 'Gallery/Uploads/'),
('gallery_abs_path', ''),
('max_file_size', '2097152');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nuke_igallery_temp`
--

DROP TABLE IF EXISTS `nuke_igallery_temp`;
CREATE TABLE IF NOT EXISTS `nuke_igallery_temp` (
  `picture_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `album_id` bigint(10) NOT NULL,
  `picture_title` varchar(80) NOT NULL,
  `picture_desc` varchar(255) NOT NULL,
  `picture_file` varchar(255) NOT NULL,
  `picture_type` tinyint(1) NOT NULL,
  `picture_date` int(11) NOT NULL,
  `picture_userid` int(10) NOT NULL,
  PRIMARY KEY (`picture_id`),
  UNIQUE KEY `picture_id` (`picture_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `nuke_igallery_temp`
--


