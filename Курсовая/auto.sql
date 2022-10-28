-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 10.66.0.14
-- Время создания: Дек 12 2021 г., 21:39
-- Версия сервера: 8.0.27
-- Версия PHP: 7.2.1RC1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `auto`
--

-- --------------------------------------------------------

--
-- Структура таблицы `car`
--

CREATE TABLE `car` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mark` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mileage` int UNSIGNED DEFAULT NULL,
  `price` int UNSIGNED DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ts` int UNSIGNED DEFAULT NULL,
  `year` int DEFAULT NULL,
  `supplier_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `car`
--

INSERT INTO `car` (`id`, `name`, `mark`, `model`, `mileage`, `price`, `color`, `ts`, `year`, `supplier_id`) VALUES
(1, 'BMW E38 Авто ', 'BMW', 'E38', 250000, 850000, 'Чёрный', 1639164136, 1998, 4),
(2, 'Ваз 2114', 'Лада', '2114', 80000, 450000, 'Белый', 1639229329, 2001, 1),
(3, 'Ваз', 'Ваз', '2112', 120000, 250000, 'Зелёный', 1639253708, 2000, 1),
(4, 'Газель', 'ГАЗ', '1', 20000, 1100000, 'Нет', 1639253738, 2018, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `car_order`
--

CREATE TABLE `car_order` (
  `id` int UNSIGNED NOT NULL,
  `car_id` int UNSIGNED DEFAULT NULL,
  `order_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `car_order`
--

INSERT INTO `car_order` (`id`, `car_id`, `order_id`) VALUES
(2, 1, 3),
(1, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `client`
--

CREATE TABLE `client` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sname` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `pat` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ts` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `client`
--

INSERT INTO `client` (`id`, `name`, `sname`, `pat`, `ts`) VALUES
(1, 'Иван', 'Иванов', 'Иванович', 1639238453),
(2, 'Петр', 'Петров', 'Петрович', 1639238585),
(3, 'Дмитрий', 'Дмитриев', 'Дмитриевич', 1639238866);

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int UNSIGNED NOT NULL,
  `ts` int UNSIGNED DEFAULT NULL,
  `total` int UNSIGNED DEFAULT NULL,
  `client_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `order`
--

INSERT INTO `order` (`id`, `ts`, `total`, `client_id`) VALUES
(1, 1639238453, 3100, 1),
(2, 1639238585, 450000, 2),
(3, 1639238866, 851300, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `order_service`
--

CREATE TABLE `order_service` (
  `id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED DEFAULT NULL,
  `order_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `order_service`
--

INSERT INTO `order_service` (`id`, `service_id`, `order_id`) VALUES
(2, 1, 1),
(1, 3, 1),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `ts` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `name`, `price`, `ts`) VALUES
(1, 'Замена масла', 1800, 1639161969),
(2, 'Замена колодок', 2000, 1639167272),
(3, 'Замена фар', 1300, 1639229081),
(4, 'Замена топливного фильтра', 2200, 1639229097);

-- --------------------------------------------------------

--
-- Структура таблицы `supplier`
--

CREATE TABLE `supplier` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ts` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `ts`) VALUES
(1, 'ВАЗ', 1639159542),
(2, 'ГАЗ', 1639159548),
(3, 'BMW Motors', 1639160363),
(4, 'Баварский Вентиляторный', 1639161004);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_foreignkey_car_supplier` (`supplier_id`);

--
-- Индексы таблицы `car_order`
--
ALTER TABLE `car_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_df89969fa96a87387ee9d7ba8ba7163750ab52a7` (`car_id`,`order_id`),
  ADD KEY `index_foreignkey_car_order_car` (`car_id`),
  ADD KEY `index_foreignkey_car_order_order` (`order_id`);

--
-- Индексы таблицы `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_foreignkey_order_client` (`client_id`);

--
-- Индексы таблицы `order_service`
--
ALTER TABLE `order_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_cdbd76506f44e43bafeb6faa0c4b03b53122ad9b` (`order_id`,`service_id`),
  ADD KEY `index_foreignkey_order_service_service` (`service_id`),
  ADD KEY `index_foreignkey_order_service_order` (`order_id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car`
--
ALTER TABLE `car`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `car_order`
--
ALTER TABLE `car_order`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `client`
--
ALTER TABLE `client`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `order_service`
--
ALTER TABLE `order_service`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `c_fk_car_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `car_order`
--
ALTER TABLE `car_order`
  ADD CONSTRAINT `c_fk_car_order_car_id` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `c_fk_car_order_order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `c_fk_order_client_id` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `order_service`
--
ALTER TABLE `order_service`
  ADD CONSTRAINT `c_fk_order_service_order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `c_fk_order_service_service_id` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
