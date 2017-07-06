-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июл 06 2017 г., 17:13
-- Версия сервера: 5.6.16
-- Версия PHP: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `calendar`
--

-- --------------------------------------------------------

--
-- Структура таблицы `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `actions`
--

INSERT INTO `actions` (`id`, `name`) VALUES
(1, 'event_create'),
(2, 'event_edit'),
(3, 'event_view'),
(4, 'event_delete');

-- --------------------------------------------------------

--
-- Структура таблицы `invite_codes`
--

CREATE TABLE IF NOT EXISTS `invite_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `invite` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `invite` (`invite`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `object_items`
--

CREATE TABLE IF NOT EXISTS `object_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_object_rights` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `id_user` int(11) NOT NULL COMMENT 'author',
  `description` text NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-object_items-id_user` (`id_user`),
  KEY `idx-object_items-id_object_rights` (`id_object_rights`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `object_items`
--

INSERT INTO `object_items` (`id`, `id_object_rights`, `name`, `id_user`, `description`, `date_start`, `date_end`) VALUES
(6, 1, 'event #2', 1, 'dgdgfg', '2017-06-12', '2017-06-12'),
(7, 1, 'event #2', 1, 'dgdgfg', '2017-06-12', '2017-06-12'),
(8, 1, 'event #2', 1, 'dgdgfg', '2017-06-12', '2017-06-12');

-- --------------------------------------------------------

--
-- Структура таблицы `object_rights`
--

CREATE TABLE IF NOT EXISTS `object_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `object_rights`
--

INSERT INTO `object_rights` (`id`, `name`) VALUES
(1, 'event');

-- --------------------------------------------------------

--
-- Структура таблицы `rights_action`
--

CREATE TABLE IF NOT EXISTS `rights_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_object_rights` int(11) NOT NULL,
  `id_rights_name` int(11) NOT NULL,
  `id_object_item` int(11) DEFAULT NULL,
  `sign` tinyint(4) NOT NULL,
  `id_action` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_action` (`id_action`),
  KEY `id_rights_name` (`id_rights_name`),
  KEY `id_object_rights` (`id_object_rights`),
  KEY `id_rights_name_2` (`id_rights_name`),
  KEY `idx-rights_action-id_object_item` (`id_object_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `rights_action`
--

INSERT INTO `rights_action` (`id`, `id_object_rights`, `id_rights_name`, `id_object_item`, `sign`, `id_action`) VALUES
(1, 1, 2, NULL, 1, 1),
(2, 1, 2, NULL, 1, 2),
(3, 1, 2, NULL, 1, 3),
(4, 1, 2, NULL, 1, 4),
(7, 1, 1, NULL, 1, 1),
(8, 1, 1, NULL, 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `rights_group`
--

CREATE TABLE IF NOT EXISTS `rights_group` (
  `id_rights_name` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_rights_name`,`id_user`),
  UNIQUE KEY `id_rights_name_2` (`id_rights_name`,`id_user`),
  UNIQUE KEY `id_rights_name_3` (`id_rights_name`,`id_user`),
  KEY `id_rights_name` (`id_rights_name`,`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `rights_group`
--

INSERT INTO `rights_group` (`id_rights_name`, `id_user`) VALUES
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rights_name`
--

CREATE TABLE IF NOT EXISTS `rights_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `one_user` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `rights_name`
--

INSERT INTO `rights_name` (`id`, `name`, `one_user`) VALUES
(1, 'User', 0),
(2, 'Admin', 0),
(3, 'admin', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `salt` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `salt`) VALUES
(1, 'admin', '11a9b53655ff9d35e8a0b05b50dba681', 'vlasova.nata.r@gmail.com', 'c6865e5e');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `invite_codes`
--
ALTER TABLE `invite_codes`
  ADD CONSTRAINT `invite_codes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rights_action`
--
ALTER TABLE `rights_action`
  ADD CONSTRAINT `fk-rights_action-id_object_rights` FOREIGN KEY (`id_object_rights`) REFERENCES `object_rights` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
