-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 04 2025 г., 11:41
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `security`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `Id` int NOT NULL COMMENT 'Код',
  `IdUser` int NOT NULL COMMENT 'Код пользователя',
  `IdPost` int NOT NULL COMMENT 'Код поста',
  `Messages` varchar(1000) NOT NULL COMMENT 'Сообщение'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`Id`, `IdUser`, `IdPost`, `Messages`) VALUES
(17, 1, 1, 'gdgdfgdf'),
(18, 8, 1, 'dfgfddfd'),
(19, 8, 1, 'weewfwesef'),
(20, 8, 1, 'ascsdsdsdfd'),
(21, 8, 1, 'awrwertewrtwerwer'),
(22, 8, 1, 'ghjgjhg'),
(23, 24, 1, 'fgdrgdrgedgdf'),
(24, 24, 1, 'ghghyytrt6yrt'),
(25, 24, 1, 'ftyhfghg'),
(26, 24, 1, 'gugggfhgfh'),
(27, 24, 1, 'ghyhyhffgfgfffg'),
(28, 24, 1, 'sdsresrewrer');

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `Ip` varchar(255) NOT NULL,
  `IdUser` int NOT NULL,
  `Date` date NOT NULL,
  `TimeOnline` time NOT NULL,
  `Event` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `logs`
--

INSERT INTO `logs` (`id`, `Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES
(42, '127.0.0.1', 24, '2025-12-04', '00:00:00', 'Пользователь  оставил комментарий к записи [Id: 1]: sdsresrewrer');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `text`, `img`) VALUES
(1, 'Внимание! Режим работы на 11 и 12 сентября 2020 г.', '11.09.2020 г. (пятница) занятия проводятся по расписанию звонков «пара-час». 12.09.2020 г. (суббота) занятия проводятся в дистанционном формате (в техникум приходить не нужно).', './img/img831.jpg\r\n'),
(2, 'Продолжается прием на заочное обучение', 'Пермский авиационный техникум продолжает прием документов на заочное обучение по специальностям «Производство авиационных двигателей», «Технология машиностроения», «Управление качеством продукции, процессов и услуг (по отраслям)» и «Прикладная информатика (по отраслям)».\r\n\r\nДокументы принимаются до 15 сентября 2020 г. включительно с 15:00 до 17:00. При себе иметь оригиналы и копии паспорта, документа об образовании, ИНН, СНИЛС и фотографии. Справки по телефону (342) 212-14-92.', './img/img830.jpg\r\n'),
(3, 'Расписание звонков', 'Расписание звонков в разных корпусах (Корпус А (1-2 и 3 этаж), Корпус В, Корпус С)\r\n<a href=\"./documents/Расписание звонков.docx\">Скачать</a>', './img/img831.jpg'),
(4, 'Основные принципы построения безопасных сайтов. Понятие безопасности приложений и классификация опасностей', 'Основные принципы построения безопасных сайтов. Понятие безопасности приложений и классификация опасностей\r\n<a href=\"./documents/1.docx\">Скачать</a>', './img/docx.png'),
(5, 'Источники угроз информационной безопасности и меры по их предотвращению', 'Источники угроз информационной безопасности и меры по их предотвращению\r\n<a href=\"./documents/2.doc\">Скачать</a>', './img/docx.png'),
(6, 'Регламенты и методы разработки безопасных веб-приложений', 'Регламенты и методы разработки безопасных веб-приложений\r\n<a href=\"./documents/3.pdf\">Скачать</a>', './img/docx.png');

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE `session` (
  `Id` int NOT NULL,
  `IdUser` int NOT NULL,
  `Ip` varchar(255) NOT NULL,
  `DateStart` datetime NOT NULL,
  `DateNow` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roll` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `roll`) VALUES
(1, 'admin', 'Asdfg123', 1),
(24, 'user', 'Asdfg123', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT COMMENT 'Код', AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `session`
--
ALTER TABLE `session`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
