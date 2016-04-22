-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 22 2016 г., 10:39
-- Версия сервера: 5.5.25
-- Версия PHP: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `parus3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cfydn_ccl_cities`
--

CREATE TABLE IF NOT EXISTS `cfydn_ccl_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL,
  `id_language` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `cfydn_ccl_cities`
--

INSERT INTO `cfydn_ccl_cities` (`id`, `name`, `id_country`, `id_language`) VALUES
(1, 'Запорожье', 1, 2),
(3, 'Краков', 2, 3),
(4, 'Киев', 1, 1),
(5, 'Мюнхен', 4, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `cfydn_ccl_countries`
--

CREATE TABLE IF NOT EXISTS `cfydn_ccl_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `cfydn_ccl_countries`
--

INSERT INTO `cfydn_ccl_countries` (`id`, `name`) VALUES
(1, 'Украина'),
(2, 'Польша'),
(3, 'Чехия'),
(4, 'Германия'),
(5, 'Франция'),
(6, 'Беларусь'),
(7, 'Россия'),
(10, 'Китай');

-- --------------------------------------------------------

--
-- Структура таблицы `cfydn_ccl_languages`
--

CREATE TABLE IF NOT EXISTS `cfydn_ccl_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `cfydn_ccl_languages`
--

INSERT INTO `cfydn_ccl_languages` (`id`, `name`) VALUES
(1, 'Украинский'),
(2, 'Русский'),
(3, 'Польский'),
(4, 'Чешский'),
(5, 'Немецкий'),
(6, 'Французский'),
(9, 'Литовский');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
