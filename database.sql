
CREATE DATABASE IF NOT EXISTS `law_office_platform`;
USE `law_office_platform`;

-- Table for Admins
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Lawyers (Multi-Tenant)
CREATE TABLE IF NOT EXISTS `lawyers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `address` VARCHAR(255),
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Clients
CREATE TABLE IF NOT EXISTS `clients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lawyer_id` INT, -- Optional: Client can register without a lawyer initially, or be assigned later
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers`(`id`) ON DELETE SET NULL
);

-- Table for Cases
CREATE TABLE IF NOT EXISTS `cases` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `lawyer_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `status` ENUM('open', 'in_progress', 'closed', 'archived') DEFAULT 'open',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers`(`id`) ON DELETE CASCADE
);

-- Table for Case Attachments
CREATE TABLE IF NOT EXISTS `case_attachments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `case_id` INT NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`case_id`) REFERENCES `cases`(`id`) ON DELETE CASCADE
);

-- Table for Consultations (written requests)
CREATE TABLE IF NOT EXISTS `consultations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `lawyer_id` INT NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `question` TEXT NOT NULL,
    `answer` TEXT,
    `status` ENUM('pending', 'answered', 'archived') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `answered_at` TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers`(`id`) ON DELETE CASCADE
);

-- Table for Chat Messages
CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `case_id` INT, -- Chat can be linked to a case or general consultation
    `client_id` INT,
    `lawyer_id` INT,
    `sender_type` ENUM('client', 'lawyer') NOT NULL,
    `message` TEXT NOT NULL,
    `attachment_path` VARCHAR(255), -- For images/files in chat
    `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`case_id`) REFERENCES `cases`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers`(`id`) ON DELETE CASCADE
);

-- Table for Notifications
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL, -- ID of the user receiving the notification (client or lawyer)
    `user_type` ENUM('client', 'lawyer') NOT NULL,
    `type` ENUM('new_case', 'case_update', 'new_message', 'consultation_reply') NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for To-Do List (per lawyer)
CREATE TABLE IF NOT EXISTS `todo_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lawyer_id` INT NOT NULL,
    `task` VARCHAR(255) NOT NULL,
    `is_completed` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers`(`id`) ON DELETE CASCADE
);

-- Insert a default admin user (for initial access)
INSERT INTO `admins` (`username`, `password`, `email`) VALUES
('admin', SHA1('admin_password'), 'admin@example.com'); -- Use a strong hashing algorithm in production

-- Insert a sample lawyer (for testing)
INSERT INTO `lawyers` (`name`, `email`, `password`, `phone`, `address`)
VALUES ('أحمد المحامي', 'ahmed.lawyer@example.com', SHA1('lawyer_password'), '0501234567', 'شارع المحامين، الرياض');

-- Insert a sample client (for testing)
INSERT INTO `clients` (`lawyer_id`, `name`, `email`, `password`, `phone`)
VALUES (1, 'فاطمة العميل', 'fatima.client@example.com', SHA1('client_password'), '0557654321');


