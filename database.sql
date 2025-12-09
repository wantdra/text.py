-- XAMPP/MySQL için tam tablo adları ile şema ve örnek veriler
CREATE DATABASE IF NOT EXISTS `almanca_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `almanca_app`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_key` VARCHAR(100) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `streak` INT UNSIGNED NOT NULL DEFAULT 1,
    `last_seen` DATE DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `reviewed_today` INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `words` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `german` VARCHAR(255) NOT NULL,
    `turkish` VARCHAR(255) NOT NULL,
    `sentence` VARCHAR(255) DEFAULT '—'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `known_words` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `word_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_user_word` (`user_id`, `word_id`),
    CONSTRAINT `fk_known_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_known_word` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `words` (`german`, `turkish`, `sentence`) VALUES
('Guten Morgen', 'Günaydın', 'Guten Morgen, wie geht es dir?'),
('Danke', 'Teşekkür ederim', 'Danke für deine Hilfe.'),
('Wie heißt du?', 'Adın ne?', 'Hallo! Wie heißt du?'),
('Ich heiße Anna', 'Benim adım Anna', 'Ich heiße Anna und ich komme aus Berlin.'),
('Woher kommst du?', 'Nerelisin?', 'Woher kommst du? Ich komme aus Izmir.');

INSERT INTO `messages` (`body`) VALUES
('Her gün küçük adımlar, büyük sonuçlar getirir.'),
('Bugün öğreneceğin her kelime yarınki konuşmanın anahtarı olacak.'),
('Düzenli tekrar, kalıcı hafızanın dostudur.'),
('Hedefine bir kelime daha yaklaştın, devam et!');
