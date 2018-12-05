-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 20 2018 г., 15:32
-- Версия сервера: 5.6.35
-- Версия PHP: 7.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `meeting`
--

-- --------------------------------------------------------

--
-- Структура таблицы `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL,
  `user_id_from` int(11) NOT NULL COMMENT 'id из таблицы ''users'' пользователя, который отправил письмо',
  `user_id_to` int(11) DEFAULT NULL COMMENT 'id из таблицы ''users'' пользователя которому отправлено письмо',
  `title` varchar(300) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Заголовок письма',
  `body` varchar(5000) NOT NULL COMMENT 'HTML-тело письма',
  `css` varchar(500) NOT NULL COMMENT 'Список файлов css письма',
  `comment` int(11) NOT NULL COMMENT 'Комментарий',
  `date_time` datetime NOT NULL COMMENT 'Дата и время отправки письма',
  `is_accepted` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Статус доставки'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `email`
--

INSERT INTO `email` (`id`, `user_id_from`, `user_id_to`, `title`, `body`, `css`, `comment`, `date_time`, `is_accepted`) VALUES
(1, 1, 3, 'e565478', '0', '0', 345, '2018-11-21 23:12:15', 0),
(2, 1, 3, 'e565478', 'asd', 'file1.css', 345, '2018-11-21 23:12:15', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `lesson`
--

CREATE TABLE IF NOT EXISTS `lesson` (
  `id` int(11) NOT NULL,
  `number` int(11) DEFAULT NULL COMMENT 'Номер урока из книги',
  `name` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Название урока',
  `is_dialog` tinyint(1) DEFAULT NULL COMMENT 'Диалог',
  `is_read` tinyint(1) DEFAULT NULL COMMENT 'Чтение',
  `is_speach` tinyint(1) DEFAULT NULL COMMENT 'Речь'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `lesson`
--

INSERT INTO `lesson` (`id`, `number`, `name`, `is_dialog`, `is_read`, `is_speach`) VALUES
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
  `date_meeting` date DEFAULT NULL COMMENT 'Дата собрания'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `meeting`
--

INSERT INTO `meeting` (`id`, `date_meeting`) VALUES
(1, '2018-10-23');

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) DEFAULT NULL COMMENT 'Id собрания (конкретная встреча с датой)',
  `task_number` int(11) NOT NULL COMMENT 'Номер задания по очереди',
  `task_type_id` int(11) DEFAULT NULL COMMENT 'Id типа задания',
  `task_source_id` int(11) DEFAULT NULL COMMENT 'Id ресурса для задания',
  `lesson_id` int(11) DEFAULT NULL COMMENT 'Id урока для задания',
  `user_id` int(11) DEFAULT NULL COMMENT 'Id пользователя ответственного за задание',
  `is_done` tinyint(2) DEFAULT NULL COMMENT 'Оценка выполнения',
  `is_hall` tinyint(2) DEFAULT NULL COMMENT 'В зале ли выполняется задание',
  `comment` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Комментарий'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `meeting_id`, `task_number`, `task_type_id`, `task_source_id`, `lesson_id`, `user_id`, `is_done`, `is_hall`, `comment`) VALUES
(1, 1, 1, 3, 2, 3, 2, 1, 1, 'Очень хорошо'),
(2, 1, 2, 4, 3, 4, 4, 0, 1, 'Не справились');

-- --------------------------------------------------------

--
-- Структура таблицы `task_partner`
--

CREATE TABLE IF NOT EXISTS `task_partner` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL COMMENT 'Id задания, для которого пользователь является партнером',
  `user_id` int(11) DEFAULT NULL COMMENT 'Id пользователя-партнера'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_partner`
--

INSERT INTO `task_partner` (`id`, `task_id`, `user_id`) VALUES
(1, 1, 4),
(2, 2, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `task_source`
--

CREATE TABLE IF NOT EXISTS `task_source` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Имя ресурса для задания',
  `short_name` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Короткое имя ресурса задания'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_source`
--

INSERT INTO `task_source` (`id`, `name`, `short_name`) VALUES
(1, 'Awake', 't'),
(2, 'Watch tower', 'w'),
(3, 'Не задано', '-');

-- --------------------------------------------------------

--
-- Структура таблицы `task_target_date`
--

CREATE TABLE IF NOT EXISTS `task_target_date` (
  `id` int(11) NOT NULL COMMENT 'id autoincrement',
  `start_date` date NOT NULL COMMENT 'Начало целевой даты заданий',
  `end_date` date NOT NULL COMMENT 'Конец целевой даты заданий'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `task_target_date`
--

INSERT INTO `task_target_date` (`id`, `start_date`, `end_date`) VALUES
(1, '2018-09-01', '2018-09-30'),
(2, '2018-10-01', '2018-10-31'),
(3, '2018-11-01', '2018-11-30'),
(4, '2018-12-01', '2018-12-31');

-- --------------------------------------------------------

--
-- Структура таблицы `task_type`
--

CREATE TABLE IF NOT EXISTS `task_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Тип задания'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_type`
--

INSERT INTO `task_type` (`id`, `name`) VALUES
(1, 'Речь'),
(3, 'Разговор 1'),
(4, 'Разговор 2'),
(5, 'Разговор 3');

-- --------------------------------------------------------

--
-- Структура таблицы `task_type_comment`
--

CREATE TABLE IF NOT EXISTS `task_type_comment` (
  `id` int(11) NOT NULL,
  `task_type_id` int(11) DEFAULT NULL COMMENT 'Id типа задания',
  `task_target_date_id` int(11) NOT NULL COMMENT 'Id целевого диапазона дат задания',
  `comment` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `task_type_comment`
--

INSERT INTO `task_type_comment` (`id`, `task_type_id`, `task_target_date_id`, `comment`) VALUES
(1, 3, 1, 'Стих там че-то Коринфянам 12:5'),
(2, 4, 2, 'Стих там че-то Притчи 1:2'),
(3, 5, 3, 'Стих там че-то Бытие 13:20'),
(4, 1, 4, 'Исход 3:10');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL COMMENT 'Id',
  `name` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Имя',
  `surname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Фамилия',
  `email` varchar(80) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'E-mail',
  `user_type_id` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Тип аккаунта',
  `login` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Логин',
  `password` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Пароль',
  `salt` varchar(10) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Соль',
  `is_ready` tinyint(1) DEFAULT '1' COMMENT 'Готовность',
  `is_ready_only_for_partnership` tinyint(1) DEFAULT '0' COMMENT 'Только партнерство',
  `comment` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Комментарий',
  `sex` tinyint(1) DEFAULT NULL COMMENT 'Пол',
  `ext` varchar(5) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Расширение картинки контакта',
  `phone` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Телефон',
  `customizable_session_values` text CHARACTER SET utf8mb4 COMMENT 'Параметры сессии пользователя',
  `session_id` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Id сессии'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `email`, `user_type_id`, `login`, `password`, `salt`, `is_ready`, `is_ready_only_for_partnership`, `comment`, `sex`, `ext`, `phone`, `customizable_session_values`, `session_id`) VALUES
(1, 'Роман', 'Скороид', 'rom-lux@rambler.ru', 2, 'admin', '7971e5c0d8443c128ac86c7af3b9ecd3', '123456789a', 1, 0, 'Глава', 1, 'jpg', '+380951065886', '{"SESSION_PAGE_NUMBER":"1","SESSION_SORTING_DIRECTION":"asc","SESSION_USER_COUNT_ON_PAGE":"1","SESSION_USER_SORTING":"surname","SESSION_USER_SEARCH_TEXT":"g"}', 'dock0f3d2jfisq68s992b6cn10'),
(2, 'Виталий', 'Бондаренко', 'vetal@rambler.ru', 1, 'vetal', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 1, 1, 'Друг с велосипедом', 1, 'jpg', '+380992356897', '', ''),
(3, 'Вячеслав', 'Рудец', 'rudec@rambler.ru', 1, 'rudec', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 1, 0, 'Друг с насосной', 1, 'jpg', '+380502224297', '', ''),
(4, 'Людмила', 'Максимова', 'maksimova@rambler.ru', 1, 'maksimova', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 1, 0, 'Подруга с детьми', 0, 'png', '+380662354297', '', ''),
(5, 'Мария', 'Рипенко', 'ripenko@rambler.ru', 1, 'ripenko', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 0, 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 0, 'png', '+380999105040', '', ''),
(6, 'Игорь', 'Дергачь', 'dergach@rambler.ru', 1, 'dergach', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 1, 1, 'Друг с женой', 1, 'jpg', '+380503256894', '', ''),
(7, 'Александр', 'Рачинский', 'rachinsky@rambler.ru', 1, 'rachinsky', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 1, 1, 'Друг старейшена', 1, 'jpg', '+380632356874', '', ''),
(8, 'Ольга', 'Кривоносова', 'olga@rambler.ru', 1, 'olga', 'a4856babce82c7c97251a339a2efa0ae', '123456789a', 1, 0, 'Ольга ольга', 0, 'jpg', '+380952356874', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `user_change_confirm`
--

CREATE TABLE IF NOT EXISTS `user_change_confirm` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'id из таблицы ''users'' пользователя поле которого предлагается изменить',
  `field` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Поле из таблицы ''users'''', которое нужно изменить',
  `value` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Старое значение',
  `new_value` varchar(500) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Новое значение',
  `hash` int(64) NOT NULL COMMENT 'Хэш',
  `comment` varchar(500) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Комментарий',
  `date_time_expires` datetime NOT NULL COMMENT 'Датаончания актуальности изменения и время ок'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Список полей конкретных пользователей, которые предлагается изменить через подтверждение на почту';

--
-- Дамп данных таблицы `user_change_confirm`
--

INSERT INTO `user_change_confirm` (`id`, `user_id`, `field`, `value`, `new_value`, `hash`, `comment`, `date_time_expires`) VALUES
(1, 1, 'password', 'e565478', 'asd', 0, '345', '2018-11-21 23:12:15');

-- --------------------------------------------------------

--
-- Структура таблицы `user_type`
--

CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(11) NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Роль аккаунта данного типа',
  `description` varchar(100) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Описание типа аккаунта',
  `permission_for_user_create_self` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_update_self` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_delete_self` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_read_self` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_create_customer` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_update_customer` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_delete_customer` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_read_customer` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_create_administrator` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_update_administrator` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_delete_administrator` tinyint(2) NOT NULL DEFAULT '0',
  `permission_for_user_read_administrator` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `user_type`
--

INSERT INTO `user_type` (`id`, `role`, `description`, `permission_for_user_create_self`, `permission_for_user_update_self`, `permission_for_user_delete_self`, `permission_for_user_read_self`, `permission_for_user_create_customer`, `permission_for_user_update_customer`, `permission_for_user_delete_customer`, `permission_for_user_read_customer`, `permission_for_user_create_administrator`, `permission_for_user_update_administrator`, `permission_for_user_delete_administrator`, `permission_for_user_read_administrator`) VALUES
(1, 'customer', 'Пользователь', 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0),
(2, 'administrator', 'Администратор', 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_partner`
--
ALTER TABLE `task_partner`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_source`
--
ALTER TABLE `task_source`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_target_date`
--
ALTER TABLE `task_target_date`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_type`
--
ALTER TABLE `task_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_type_comment`
--
ALTER TABLE `task_type_comment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_change_confirm`
--
ALTER TABLE `user_change_confirm`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `meeting`
--
ALTER TABLE `meeting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `task_partner`
--
ALTER TABLE `task_partner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `task_source`
--
ALTER TABLE `task_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `task_target_date`
--
ALTER TABLE `task_target_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id autoincrement',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `task_type`
--
ALTER TABLE `task_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `task_type_comment`
--
ALTER TABLE `task_type_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `user_change_confirm`
--
ALTER TABLE `user_change_confirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
