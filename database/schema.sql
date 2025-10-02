CREATE DATABASE IF NOT EXISTS `multi_lawyers_office`;

USE `multi_lawyers_office`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('client', 'lawyer', 'admin') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `cases` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lawyer_id` INT NOT NULL,
  `client_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `details` TEXT NOT NULL,
  `status` ENUM('new', 'in_progress', 'closed', 'archived') NOT NULL DEFAULT 'new',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lawyer_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`client_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `case_id` INT NOT NULL,
  `sender_id` INT NOT NULL,
  `message` TEXT,
  `attachment` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`case_id`) REFERENCES `cases`(`id`),
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `consultations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lawyer_id` INT NOT NULL,
  `client_id` INT NOT NULL,
  `question` TEXT NOT NULL,
  `answer` TEXT,
  `status` ENUM('pending', 'answered') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lawyer_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`client_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `message` VARCHAR(255) NOT NULL,
  `is_read` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

