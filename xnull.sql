-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `xnull` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `xnull`;

CREATE TABLE `game` (
  `id` int(11) NOT NULL DEFAULT '0',
  `board_position` char(9) COLLATE utf8_bin NOT NULL DEFAULT '---------',
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `room` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `room` (`id`, `name`) VALUES
(1,	'room_1'),
(2,	'room_2'),
(3,	'room_3');

CREATE TABLE `users` (
  `session_name` varchar(128) COLLATE utf8_bin NOT NULL,
  `room_id` int(11) NOT NULL DEFAULT '0',
  `game_id` int(11) NOT NULL DEFAULT '0',
  `game_type_xo` char(1) COLLATE utf8_bin NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- 2016-06-17 06:39:12
