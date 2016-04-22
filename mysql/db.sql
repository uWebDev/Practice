
-- Создание базы данных
CREATE DATABASE IF NOT EXISTS `dore`;

-- Удаление базы данных
#DROP DATABASE IF NOT EXISTS `dore`;

-- Использовать базу данных
USE `dore`;



-- Создание таблицы Города
CREATE TABLE IF NOT EXISTS `dore`.`cities` (
  `id` INT UNSIGNED NOT NULL COMMENT '',
  `city` VARCHAR(255) NOT NULL COMMENT '',
  `countries_id` INT UNSIGNED NOT NULL COMMENT '',
  UNIQUE INDEX `id` (`id`)  COMMENT '',
  INDEX `fk_cities_countries_idx` (`countries_id`)  COMMENT '')
ENGINE = MyISAM
COLLATE = utf8_general_ci;

-- Удаление таблицы Города
#DROP TABLE IF EXISTS `cities`;



-- Создание таблицы Страны
CREATE TABLE IF NOT EXISTS `dore`.`countries` (
  `id` INT UNSIGNED NOT NULL COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `currency_code` VARCHAR(5) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = MyISAM
COLLATE = utf8_general_ci;

-- Удаление таблицы Страны
#DROP TABLE IF EXISTS `countries`;