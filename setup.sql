-- ============================================================
-- PawHaven — Premium Animal Adoption Management
-- Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS `pawhaven`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `pawhaven`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (username: admin, password: admin123)
INSERT IGNORE INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$jBMbzg8Ivbds.dchs23e5ubcWF7Qji0Whl.soORz4sNLJ07uMdqXW');

CREATE TABLE IF NOT EXISTS `animals` (
  `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(100)  NOT NULL,
  `species`       ENUM('Dog','Cat','Bird','Rabbit','Fish','Other') NOT NULL,
  `color`         VARCHAR(60)   NOT NULL,
  `age`           INT UNSIGNED  NOT NULL COMMENT 'Age in months',
  `gender`        ENUM('Male','Female') NOT NULL,
  `health_status` ENUM('Healthy','Under Treatment') NOT NULL DEFAULT 'Healthy',
  `image_path`    VARCHAR(255)  NULL,
  `status`        ENUM('Available', 'Adopted') NOT NULL DEFAULT 'Available',
  `adopted_at`    TIMESTAMP     NULL,
  `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
