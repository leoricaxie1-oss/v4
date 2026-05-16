-- ============================================================================
-- PanipOne — Barangay Management System
-- MySQL / MariaDB schema for XAMPP phpMyAdmin import.
--
-- Usage:
--   1. Open phpMyAdmin (http://localhost/phpmyadmin).
--   2. Create a database named `panipone` (collation: utf8mb4_unicode_ci).
--   3. Select it, click "Import", choose this file, and run.
--
-- This file is idempotent for fresh databases: it drops the included tables
-- if they exist, then recreates them with seed data.
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = '+08:00';

-- ----------------------------------------------------------------------------
-- Drop in reverse dependency order
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `complaints`;
DROP TABLE IF EXISTS `document_requests`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `businesses`;
DROP TABLE IF EXISTS `skill_services`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `users`;

-- ----------------------------------------------------------------------------
-- users
-- ----------------------------------------------------------------------------
CREATE TABLE `users` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(150) NOT NULL,
    `email`         VARCHAR(190) NOT NULL,
    `phone`         VARCHAR(40)  NULL,
    `address`       VARCHAR(255) NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role`          ENUM('resident','tanod','secretary','kagawad','captain','admin')
                    NOT NULL DEFAULT 'resident',
    `status`        ENUM('pending','active','suspended','rejected')
                    NOT NULL DEFAULT 'pending',
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_role_status_idx` (`role`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- announcements
-- ----------------------------------------------------------------------------
CREATE TABLE `announcements` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`         VARCHAR(190) NOT NULL,
    `body`          TEXT NOT NULL,
    `posted_by`     INT UNSIGNED NULL,
    `is_published`  TINYINT(1) NOT NULL DEFAULT 1,
    `posted_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `announcements_posted_by_fk` (`posted_by`),
    CONSTRAINT `fk_announcements_posted_by`
        FOREIGN KEY (`posted_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- skill_services
-- ----------------------------------------------------------------------------
CREATE TABLE `skill_services` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `title`       VARCHAR(150) NOT NULL,
    `category`    VARCHAR(80)  NOT NULL,
    `description` TEXT NULL,
    `status`      ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `skill_services_user_id_fk` (`user_id`),
    CONSTRAINT `fk_skill_services_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- businesses
-- ----------------------------------------------------------------------------
CREATE TABLE `businesses` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `name`       VARCHAR(150) NOT NULL,
    `category`   VARCHAR(80)  NOT NULL,
    `address`    VARCHAR(255) NOT NULL,
    `status`     ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `businesses_user_id_fk` (`user_id`),
    CONSTRAINT `fk_businesses_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- reviews
-- ----------------------------------------------------------------------------
CREATE TABLE `reviews` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `target_type` ENUM('skill','business') NOT NULL,
    `target_id`  INT UNSIGNED NOT NULL,
    `rating`     TINYINT UNSIGNED NOT NULL,
    `comment`    TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `reviews_target_idx` (`target_type`, `target_id`),
    KEY `reviews_user_id_fk` (`user_id`),
    CONSTRAINT `fk_reviews_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- document_requests
-- ----------------------------------------------------------------------------
CREATE TABLE `document_requests` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       INT UNSIGNED NOT NULL,
    `document_type` VARCHAR(100) NOT NULL,
    `purpose`       TEXT NOT NULL,
    `status`        ENUM('pending','approved','released','rejected') NOT NULL DEFAULT 'pending',
    `requested_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `picked_up_at`  DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `document_requests_user_id_fk` (`user_id`),
    CONSTRAINT `fk_document_requests_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- complaints
-- ----------------------------------------------------------------------------
CREATE TABLE `complaints` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `subject`    VARCHAR(190) NOT NULL,
    `category`   VARCHAR(80)  NOT NULL,
    `details`    TEXT NOT NULL,
    `status`     ENUM('open','mediation','hearing','resolved','closed') NOT NULL DEFAULT 'open',
    `filed_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `resolved_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `complaints_user_id_fk` (`user_id`),
    CONSTRAINT `fk_complaints_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- appointments
-- ----------------------------------------------------------------------------
CREATE TABLE `appointments` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `purpose`    VARCHAR(190) NOT NULL,
    `scheduled_at` DATETIME NOT NULL,
    `status`     ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `appointments_user_id_fk` (`user_id`),
    CONSTRAINT `fk_appointments_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- notifications
-- ----------------------------------------------------------------------------
CREATE TABLE `notifications` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `title`      VARCHAR(190) NOT NULL,
    `body`       TEXT NULL,
    `read_at`    DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `notifications_user_id_fk` (`user_id`),
    CONSTRAINT `fk_notifications_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- Seed data
-- ============================================================================
--
-- Seed users have a bcrypt hash of the password "password" (length 60).
-- Change passwords immediately after first login.
--

INSERT INTO `users` (`name`, `email`, `phone`, `address`, `password_hash`, `role`, `status`) VALUES
    ('Captain Juan Dela Cruz', 'captain@panipone.local',  '09171234567', 'Purok 1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'captain',  'active'),
    ('Secretary Maria Santos', 'secretary@panipone.local', '09181234567', 'Purok 2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'secretary','active'),
    ('Kagawad Jose Reyes',     'kagawad@panipone.local',   '09191234567', 'Purok 3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kagawad',  'active'),
    ('Resident Pedro Garcia',  'resident@panipone.local',  '09201234567', 'Purok 4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'resident', 'active'),
    ('Pending Resident Ana',   'pending@panipone.local',   '09211234567', 'Purok 5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'resident', 'pending');

INSERT INTO `announcements` (`title`, `body`, `posted_by`, `is_published`, `posted_at`) VALUES
    ('Welcome to Barangay Panipuan',
     'This portal lets residents request documents, file complaints, browse skills and services, and receive announcements.',
     1, 1, NOW() - INTERVAL 2 DAY),
    ('Monthly community clean-up',
     'Join the monthly clean-up drive every first Saturday of the month from 6 AM at the barangay hall.',
     2, 1, NOW() - INTERVAL 1 DAY),
    ('Document pickup schedule',
     'Document requests submitted before 12 NN are available for pickup the next business day from 9 AM to 5 PM.',
     1, 1, NOW());

INSERT INTO `skill_services` (`user_id`, `title`, `category`, `description`, `status`) VALUES
    (4, 'Home electrical repair', 'Electrician', 'Wiring fixes, breaker installs, and outlet replacements.', 'approved'),
    (4, 'Math & Science tutoring', 'Tutoring',    'Algebra and basic chemistry tutoring for grade school and high school students.', 'approved');

INSERT INTO `businesses` (`user_id`, `name`, `category`, `address`, `status`) VALUES
    (4, 'Garcia Sari-Sari Store',  'Retail',  '#12 Purok 4, Barangay Panipuan', 'approved'),
    (4, 'Pedro Bakeshop',          'Food',    '#14 Purok 4, Barangay Panipuan', 'approved');

INSERT INTO `document_requests` (`user_id`, `document_type`, `purpose`, `status`, `requested_at`) VALUES
    (4, 'Barangay Clearance',    'Employment requirement.',     'approved', NOW() - INTERVAL 3 DAY),
    (4, 'Certificate of Residency', 'School enrollment.',       'pending',  NOW() - INTERVAL 1 DAY);

INSERT INTO `complaints` (`user_id`, `subject`, `category`, `details`, `status`, `filed_at`) VALUES
    (4, 'Noise complaint vs. neighbor', 'Noise', 'Loud videoke past midnight on weekends.', 'open', NOW() - INTERVAL 2 DAY);

INSERT INTO `notifications` (`user_id`, `title`, `body`, `created_at`) VALUES
    (4, 'Document approved', 'Your Barangay Clearance is ready for pickup.', NOW() - INTERVAL 12 HOUR),
    (4, 'Welcome to PanipOne', 'Your account has been activated. Explore the dashboard.', NOW() - INTERVAL 7 DAY);
