-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: erato
-- Tiempo de generación: 31-08-2012 a las 12:51:00
-- Versión del servidor: 5.5.27-1~dotdeb.0-log
-- Versión de PHP: 5.3.16-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `argentinacomparte`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tooltip` text,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `order_UNIQUE` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `copy` text,
  `body` text,
  `user` int(10) unsigned NOT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `creation_date` date NOT NULL,
  `modification_date` date DEFAULT NULL,
  `news_id` int(11) DEFAULT NULL,
  `active` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`user`),
  KEY `fk_news_user` (`user`),
  KEY `fk_news_user1` (`modified_by`),
  KEY `fk_news_news1` (`news_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=234 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `news_id` int(11) NOT NULL,
  `highlight` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`news_id`),
  KEY `fk_images_news1` (`news_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19564 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(62) DEFAULT NULL,
  `copy` text,
  `body` text,
  `mintit` varchar(45) DEFAULT NULL,
  `youtube` varchar(45) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `creation_date` date NOT NULL,
  `modification_date` date DEFAULT NULL,
  `preferential_category` int(10) unsigned DEFAULT NULL,
  `active` int(2) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`user`),
  KEY `fk_news_user` (`user`),
  KEY `fk_news_user1` (`modified_by`),
  KEY `fk_news_category1` (`preferential_category`),
  KEY `fk_news_news1` (`news_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=270 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news_has_category`
--

CREATE TABLE IF NOT EXISTS `news_has_category` (
  `news_id` int(11) NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`news_id`,`category_id`),
  KEY `fk_news_has_category_category1` (`category_id`),
  KEY `fk_news_has_category_news1` (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predeterminar`
--

CREATE TABLE IF NOT EXISTS `predeterminar` (
  `element` varchar(50) NOT NULL,
  `value` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite`
--

CREATE TABLE IF NOT EXISTS `tramite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_category1` FOREIGN KEY (`preferential_category`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_news_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_news_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_news_user1` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `news_has_category`
--
ALTER TABLE `news_has_category`
  ADD CONSTRAINT `fk_news_has_category_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_news_has_category_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
