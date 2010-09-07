-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-06-2010 a las 19:29:21
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `ravennuke`
--

-- --------------------------------------------------------

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Volcar la base de datos para la tabla `nuke_igallery_albums`
--

INSERT INTO `nuke_igallery_albums` (`album_id`, `album_left`, `album_right`, `album_active`, `album_title`, `album_desc`, `album_folder`, `album_cover`, `album_date`) VALUES
(1, 1, 8, 1, 'Root', 'This is the root album, please don''t remove it from database. Be aware that your main categories must be child of Root album.', '', '', 0),
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
(57, 2, 10, 'tremendidimo', 1276977753),
(58, 2, 10, 'tremendisimo 2', 1276977753),
(59, 2, 3, '123 probando', 1276977753),
(60, 2, 3, '123 probando', 1276977753),
(61, 2, 3, 'Yet another test comment', 1276977753),
(62, 2, 3, 'Yet another one...', 1276980959),
(63, 2, 8, 'cutest russian girl... for sure!!!', 1276981878),
(64, 2, 10, 'ok ok ok', 1276985811),
(65, 2, 3, 'Sed ultrices accumsan velit vel porta. Vestibulum eget nunc sem, eget cursus odio. Nullam eu sem vitae leo blandit pretium eget pulvinar odio. Mauris feugiat ante non libero aliquam egestas. Sed et dolor augue. Mauris faucibus nulla at ligula feugiat vel consequat ante pulvinar. In dolor elit, molestie eget faucibus at, convallis in nibh. Proin eros augue, facilisis non rhoncus ut, ornare sed leo. Donec quam enim, elementum id suscipit non, eleifend eget felis. Ut quis ligula sed libero faucibus convallis. Nunc rutrum lacus et erat dictum ut convallis nulla sollicitudin. Praesent eu nunc elit. Praesent scelerisque magna interdum neque lobortis adipiscing. Nulla fringilla aliquet dapibus. Curabitur scelerisque nulla eget nulla interdum nec eleifend elit dictum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam rhoncus, mi quis volutpat dapibus, justo massa lobortis augue, quis scelerisque odio nunc sit amet dolor. Suspendisse venenatis, mi vitae sagittis lobortis, sapien orci malesuada enim, eget placerat sapien neque vitae sem. Sed ligula nibh, venenatis vulputate laoreet quis, mollis et ipsum. Quisque pellentesque, dui nec consequat euismod, massa elit semper risus, at vestibulum ligula ipsum vel nisi.', 1277002156);

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
  `picture_counter` int(10) NOT NULL,
  `picture_date` int(11) NOT NULL,
  `picture_userid` int(10) NOT NULL,
  `picture_rating` int(10) NOT NULL DEFAULT '0',
  `picture_votes` int(10) NOT NULL DEFAULT '0',
  `picture_lastrate` int(11) NOT NULL,
  PRIMARY KEY (`picture_id`),
  UNIQUE KEY `picture_id` (`picture_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Volcar la base de datos para la tabla `nuke_igallery_pictures`
--

INSERT INTO `nuke_igallery_pictures` (`picture_id`, `album_id`, `picture_title`, `picture_desc`, `picture_file`, `picture_counter`, `picture_date`, `picture_userid`, `picture_rating`, `picture_votes`, `picture_lastrate`) VALUES
(1, 2, 'Odalys Garcia', 'A Cuban actress, model, singer and show host.', 'odalys_garcia.jpg', 16, 10, 0, 0, 0, 0),
(2, 2, 'Eva Mendes', 'An American actress of cuban parents', 'eva_mendes.jpg', 14, 10, 0, 0, 0, 0),
(3, 2, 'Adriana Lima', 'A Brazilian supermodel best known as a Victoria''s Secret Angel', 'adriana_lima.jpg', 98, 10, 0, 20, 4, 2010),
(4, 2, 'Vida Guerra', 'A cuban singer, actress and model', 'vida_guerra.jpg', 8, 10, 0, 0, 0, 0),
(5, 2, 'Zoe Saldana', 'An american actress of dominican parents', 'zoe_saldana.jpg', 13, 10, 0, 0, 0, 0),
(6, 2, 'Carmen Elektra', 'An american glamour model, actress, television personality, singer, dancer and sex symbol', 'carmen_electra.jpg', 12, 10, 0, 0, 0, 0),
(7, 2, 'Michelle Rodriguez', 'An american actress of dominican and puertorican parents', 'michelle_rodriguez.jpg', 6, 10, 0, 0, 0, 0),
(8, 2, 'Anna Kournikova', 'A retired russian professional tennis player and model', 'anna_kournikova.jpg', 32, 10, 0, 11, 3, 2010),
(9, 2, 'Martha Heredia', 'Dominican singer. Winner of 2009''s Latin American Idol contest', 'martha_heredia.jpg', 10, 10, 0, 4, 1, 0),
(10, 2, 'Amelia Vega', 'Dominican model and singer. Winner of 2003''s Miss Universo contest', 'amelia_vega.jpg', 22, 10, 0, 10, 2, 0),
(11, 2, 'catherine_zeta_jones.jpg', '', 'catherine_zeta_jones.jpg', 7, 19, 2, 0, 0, 0);

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
('gallery_abs_path', ''),
('pictures_path', 'Gallery/Pictures/'),
('thumbs_path', 'Gallery/Thumbs/'),
('upload_path', 'Gallery/Uploads/'),
('show_rows', '5'),
('show_columns', '4'),
('show_details', '1'),
('upload_resize', '1'),
('upload_autosize', '720'),
('max_quality', '90'),
('thumbs_autosize', '150'),
('thumbs_quality', '75'),
('thumbs_format', 'JPG'),
('comments_allow', '1'),
('comments_public', '1'),
('comments_autoapprove', '0'),
('pop_count', '10'),
('ext_allow', 'png,jpeg,jpg,jpe,gif');

