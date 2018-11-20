-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 29 2018 г., 16:07
-- Версия сервера: 5.6.35
-- Версия PHP: 7.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `roman`
--

-- --------------------------------------------------------

--
-- Структура таблицы `lessons`
--

CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int(11) NOT NULL,
  `number` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `is_dialog` tinyint(1) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `is_speach` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `lessons`
--

INSERT INTO `lessons` (`id`, `number`, `name`, `is_dialog`, `is_read`, `is_speach`) VALUES
(1, 1, 'Some lesson', 1, 0, 0),
(2, 2, 'Public speach', 0, 1, 0),
(3, 3, 'Работа с возражениями', 0, 0, 1),
(4, 14, 'Ответ на запрет', 0, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `meeting`
--

CREATE TABLE IF NOT EXISTS `meeting` (
  `id` int(11) NOT NULL,
  `date_meeting` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `meeting`
--

INSERT INTO `meeting` (`id`, `date_meeting`) VALUES
(1, '2018-10-23');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `task_number` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_done` tinyint(1) DEFAULT NULL,
  `is_hall` tinyint(1) DEFAULT NULL,
  `comment` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `meeting_id`, `task_number`, `type_id`, `source_id`, `lesson_id`, `user_id`, `is_done`, `is_hall`, `comment`) VALUES
(1, 1, 1, 3, 2, 3, 2, 1, 1, 'Очень хорошо'),
(2, 1, 2, 4, 3, 4, 4, 0, 1, 'Не справились');

-- --------------------------------------------------------

--
-- Структура таблицы `task_partners`
--

CREATE TABLE IF NOT EXISTS `task_partners` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_partners`
--

INSERT INTO `task_partners` (`id`, `task_id`, `user_id`) VALUES
(1, 1, 4),
(2, 2, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `task_sources`
--

CREATE TABLE IF NOT EXISTS `task_sources` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `short_name` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_sources`
--

INSERT INTO `task_sources` (`id`, `name`, `short_name`) VALUES
(1, 'Awake', 't'),
(2, 'Watch tower', 'w'),
(3, 'Не задано', '-');

-- --------------------------------------------------------

--
-- Структура таблицы `task_types`
--

CREATE TABLE IF NOT EXISTS `task_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_types`
--

INSERT INTO `task_types` (`id`, `name`) VALUES
(1, 'Речь'),
(3, 'Разговор 1'),
(4, 'Разговор 2'),
(5, 'Разговор 3');

-- --------------------------------------------------------

--
-- Структура таблицы `task_types_comments`
--

CREATE TABLE IF NOT EXISTS `task_types_comments` (
  `id` int(11) NOT NULL,
  `task_type_id` int(11) DEFAULT NULL,
  `comment` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_types_comments`
--

INSERT INTO `task_types_comments` (`id`, `task_type_id`, `comment`, `date_start`, `date_end`) VALUES
(1, 3, 'Стих там че-то Коринфянам 12:5', '2018-10-01', '2018-10-31'),
(2, 4, 'Стих там че-то Притчи 1:2', '2018-10-01', '2018-10-31'),
(3, 5, 'Стих там че-то Бытие 13:20', '2018-10-01', '2018-10-31'),
(4, 1, 'Исход 3:10', '2018-10-01', '2018-10-31');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `surname` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `is_ready` tinyint(1) DEFAULT NULL,
  `is_ready_only_for_partnership` tinyint(1) DEFAULT NULL,
  `comment` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `is_ready`, `is_ready_only_for_partnership`, `comment`, `sex`, `phone`) VALUES
(1, 'Роман', 'Скороид', 1, 0, 'Глава', 1, '+380951065886'),
(2, 'Виталий', 'Бондаренко', 1, 1, 'Друг с велосипедом', 1, '+380992356897'),
(3, 'Вячеслав', 'Рудец', 1, 0, 'Друг с насосной', 1, '+380502224297'),
(4, 'Людмила', 'Максимова', 1, 0, 'Подруга с детьми', 0, '+380662354297'),
(5, 'Мария', 'Рипенко', 0, 0, 'Бабауля', 0, '+380999105040'),
(6, 'Игорь', 'Дергачь', 1, 1, 'Друг с женой', 1, '+380503256894'),
(7, 'Александр', 'Рачинский', 1, 1, 'Друг старейшена', 1, '+380632356874');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_partners`
--
ALTER TABLE `task_partners`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_sources`
--
ALTER TABLE `task_sources`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_types`
--
ALTER TABLE `task_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_types_comments`
--
ALTER TABLE `task_types_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `meeting`
--
ALTER TABLE `meeting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `task_partners`
--
ALTER TABLE `task_partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `task_sources`
--
ALTER TABLE `task_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `task_types`
--
ALTER TABLE `task_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `task_types_comments`
--
ALTER TABLE `task_types_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
