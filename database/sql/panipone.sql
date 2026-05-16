-- =============================================================================
-- PanipOne — Barangay Management System
-- Full schema + base seed data for XAMPP MySQL / MariaDB (phpMyAdmin).
--
-- HOW TO IMPORT (phpMyAdmin):
--   1. Start XAMPP (Apache + MySQL).
--   2. Open phpMyAdmin → click "New" → create a database named  panipone
--      (Collation: utf8mb4_unicode_ci).
--   3. Select the panipone database → "Import" tab → choose this file →
--      "Go". phpMyAdmin will run every statement below.
--
-- HOW TO IMPORT (mysql CLI from XAMPP shell):
--   mysql -u root -p panipone < database/sql/panipone.sql
--
-- WHAT YOU GET:
--   - All 38 tables (InnoDB, utf8mb4_unicode_ci) with PK / FK / indexes,
--     timestamps, soft-deletes, ENUMs, JSON columns.
--   - Spatie roles + permissions + role↔permission map.
--   - Geography (Pampanga → City of San Fernando → Panipuan → 7 puroks).
--   - 5 staff accounts (admin / captain / kagawad / secretary / tanod).
--   - 3 published announcements authored by the captain account.
--   - Pre-populated `migrations` table so `php artisan migrate` will NOT
--     re-run these migrations after import.
--
-- DEFAULT LOGIN CREDENTIALS (PLEASE CHANGE IMMEDIATELY AFTER FIRST LOGIN):
--   admin@panipuan.gov.ph      password
--   captain@panipuan.gov.ph    password
--   kagawad@panipuan.gov.ph    password
--   secretary@panipuan.gov.ph  password
--   tanod@panipuan.gov.ph      password
--
-- The hash below is the well-known Laravel test hash for the literal string
-- "password" (bcrypt cost 10). After importing, log in and update each
-- account's password via the app or set a new bcrypt hash in phpMyAdmin.
-- =============================================================================

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- -----------------------------------------------------------------------------
-- Drop existing tables (safe to run on a fresh DB; ignored if not present)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `login_histories`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `hearing_schedule`;
DROP TABLE IF EXISTS `mediation_schedule`;
DROP TABLE IF EXISTS `blotter_records`;
DROP TABLE IF EXISTS `complaint_evidence`;
DROP TABLE IF EXISTS `complaints`;
DROP TABLE IF EXISTS `documents`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `conversation_user`;
DROP TABLE IF EXISTS `conversations`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `businesses`;
DROP TABLE IF EXISTS `skill_services`;
DROP TABLE IF EXISTS `residents`;
DROP TABLE IF EXISTS `households`;
DROP TABLE IF EXISTS `puroks`;
DROP TABLE IF EXISTS `barangays`;
DROP TABLE IF EXISTS `cities`;
DROP TABLE IF EXISTS `provinces`;
DROP TABLE IF EXISTS `role_has_permissions`;
DROP TABLE IF EXISTS `model_has_roles`;
DROP TABLE IF EXISTS `model_has_permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;

-- =============================================================================
-- Framework tables
-- =============================================================================

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) NOT NULL,
  `middle_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) NOT NULL,
  `suffix` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(191) NOT NULL,
  `avatar_path` varchar(191) DEFAULT NULL,
  `account_status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `approved_by_secretary` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_kagawad` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_captain` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_secretary_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_kagawad_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_captain_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_secretary_at` timestamp NULL DEFAULT NULL,
  `approved_by_kagawad_at` timestamp NULL DEFAULT NULL,
  `approved_by_captain_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `failed_login_attempts` int UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  KEY `users_account_status_index` (`account_status`),
  KEY `users_approved_by_secretary_id_foreign` (`approved_by_secretary_id`),
  KEY `users_approved_by_kagawad_id_foreign` (`approved_by_kagawad_id`),
  KEY `users_approved_by_captain_id_foreign` (`approved_by_captain_id`),
  CONSTRAINT `users_approved_by_secretary_id_foreign` FOREIGN KEY (`approved_by_secretary_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_approved_by_kagawad_id_foreign`   FOREIGN KEY (`approved_by_kagawad_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_approved_by_captain_id_foreign`   FOREIGN KEY (`approved_by_captain_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Spatie roles & permissions
-- =============================================================================

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign`       FOREIGN KEY (`role_id`)       REFERENCES `roles` (`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Geography
-- =============================================================================

CREATE TABLE `provinces` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `region` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provinces_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `province_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_province_id_name_unique` (`province_id`,`name`),
  CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `barangays` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barangays_city_id_name_unique` (`city_id`,`name`),
  CONSTRAINT `barangays_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `puroks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `barangay_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `puroks_barangay_id_name_unique` (`barangay_id`,`name`),
  CONSTRAINT `puroks_barangay_id_foreign` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Households & residents
-- =============================================================================

CREATE TABLE `households` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `household_code` varchar(191) NOT NULL,
  `purok_id` bigint UNSIGNED NOT NULL,
  `street_address` varchar(191) DEFAULT NULL,
  `head_resident_id` bigint UNSIGNED DEFAULT NULL,
  `members_count` int UNSIGNED NOT NULL DEFAULT 0,
  `utilities` json DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `households_household_code_unique` (`household_code`),
  KEY `households_purok_id_foreign` (`purok_id`),
  KEY `households_head_resident_id_foreign` (`head_resident_id`),
  CONSTRAINT `households_purok_id_foreign` FOREIGN KEY (`purok_id`) REFERENCES `puroks` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `residents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `household_id` bigint UNSIGNED DEFAULT NULL,
  `province_id` bigint UNSIGNED NOT NULL,
  `city_id` bigint UNSIGNED NOT NULL,
  `barangay_id` bigint UNSIGNED NOT NULL,
  `purok_id` bigint UNSIGNED NOT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('male','female','other') DEFAULT NULL,
  `civil_status` enum('single','married','widowed','separated','divorced') DEFAULT NULL,
  `occupation` varchar(191) DEFAULT NULL,
  `citizenship` varchar(191) NOT NULL DEFAULT 'Filipino',
  `religion` varchar(191) DEFAULT NULL,
  `is_senior_citizen` tinyint(1) NOT NULL DEFAULT 0,
  `is_pwd` tinyint(1) NOT NULL DEFAULT 0,
  `is_solo_parent` tinyint(1) NOT NULL DEFAULT 0,
  `is_voter` tinyint(1) NOT NULL DEFAULT 0,
  `is_household_head` tinyint(1) NOT NULL DEFAULT 0,
  `emergency_contact_name` varchar(191) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_relation` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `residents_user_id_unique` (`user_id`),
  KEY `residents_household_id_foreign` (`household_id`),
  KEY `residents_province_id_foreign` (`province_id`),
  KEY `residents_city_id_foreign` (`city_id`),
  KEY `residents_barangay_id_foreign` (`barangay_id`),
  KEY `residents_purok_id_foreign` (`purok_id`),
  KEY `residents_is_senior_citizen_index` (`is_senior_citizen`),
  KEY `residents_is_pwd_index` (`is_pwd`),
  KEY `residents_is_solo_parent_index` (`is_solo_parent`),
  KEY `residents_purok_id_is_senior_citizen_index` (`purok_id`,`is_senior_citizen`),
  KEY `residents_purok_id_is_pwd_index` (`purok_id`,`is_pwd`),
  CONSTRAINT `residents_user_id_foreign`      FOREIGN KEY (`user_id`)      REFERENCES `users` (`id`)      ON DELETE CASCADE,
  CONSTRAINT `residents_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`) ON DELETE SET NULL,
  CONSTRAINT `residents_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `residents_city_id_foreign`     FOREIGN KEY (`city_id`)     REFERENCES `cities` (`id`)    ON DELETE RESTRICT,
  CONSTRAINT `residents_barangay_id_foreign` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `residents_purok_id_foreign`    FOREIGN KEY (`purok_id`)    REFERENCES `puroks` (`id`)    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `households`
  ADD CONSTRAINT `households_head_resident_id_foreign`
  FOREIGN KEY (`head_resident_id`) REFERENCES `residents` (`id`) ON DELETE SET NULL;

-- =============================================================================
-- Skills & businesses
-- =============================================================================

CREATE TABLE `skill_services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `category` varchar(191) NOT NULL,
  `custom_category` varchar(191) DEFAULT NULL,
  `title` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `rate_unit` varchar(191) DEFAULT NULL,
  `contact_email` varchar(191) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `photo_path` varchar(191) DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by_secretary` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_kagawad` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_captain` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_secretary_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_kagawad_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_captain_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_secretary_at` timestamp NULL DEFAULT NULL,
  `approved_by_kagawad_at` timestamp NULL DEFAULT NULL,
  `approved_by_captain_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` int UNSIGNED NOT NULL DEFAULT 0,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `rating_count` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `skill_services_user_id_foreign` (`user_id`),
  KEY `skill_services_status_index` (`status`),
  KEY `skill_services_approved_by_secretary_id_foreign` (`approved_by_secretary_id`),
  KEY `skill_services_approved_by_kagawad_id_foreign`   (`approved_by_kagawad_id`),
  KEY `skill_services_approved_by_captain_id_foreign`   (`approved_by_captain_id`),
  CONSTRAINT `skill_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `skill_services_approved_by_secretary_id_foreign` FOREIGN KEY (`approved_by_secretary_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `skill_services_approved_by_kagawad_id_foreign`   FOREIGN KEY (`approved_by_kagawad_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `skill_services_approved_by_captain_id_foreign`   FOREIGN KEY (`approved_by_captain_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `businesses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_user_id` bigint UNSIGNED NOT NULL,
  `business_name` varchar(191) NOT NULL,
  `business_type` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(191) NOT NULL,
  `purok_id` bigint UNSIGNED DEFAULT NULL,
  `contact_email` varchar(191) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `logo_path` varchar(191) DEFAULT NULL,
  `permit_number` varchar(191) DEFAULT NULL,
  `permit_issued_at` date DEFAULT NULL,
  `permit_expires_at` date DEFAULT NULL,
  `last_inspection_at` date DEFAULT NULL,
  `next_inspection_at` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `approved_by_secretary` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_kagawad` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_captain` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_secretary_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_kagawad_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_captain_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_secretary_at` timestamp NULL DEFAULT NULL,
  `approved_by_kagawad_at` timestamp NULL DEFAULT NULL,
  `approved_by_captain_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `rating_count` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `businesses_permit_number_unique` (`permit_number`),
  KEY `businesses_owner_user_id_foreign` (`owner_user_id`),
  KEY `businesses_purok_id_foreign` (`purok_id`),
  KEY `businesses_status_index` (`status`),
  KEY `businesses_approved_by_secretary_id_foreign` (`approved_by_secretary_id`),
  KEY `businesses_approved_by_kagawad_id_foreign`   (`approved_by_kagawad_id`),
  KEY `businesses_approved_by_captain_id_foreign`   (`approved_by_captain_id`),
  CONSTRAINT `businesses_owner_user_id_foreign` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `businesses_purok_id_foreign`     FOREIGN KEY (`purok_id`)     REFERENCES `puroks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `businesses_approved_by_secretary_id_foreign` FOREIGN KEY (`approved_by_secretary_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `businesses_approved_by_kagawad_id_foreign`   FOREIGN KEY (`approved_by_kagawad_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `businesses_approved_by_captain_id_foreign`   FOREIGN KEY (`approved_by_captain_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `reviewable_type` varchar(191) NOT NULL,
  `reviewable_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`),
  KEY `reviews_reviewable_type_reviewable_id_rating_index` (`reviewable_type`,`reviewable_id`,`rating`),
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Messaging
-- =============================================================================

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject` varchar(191) DEFAULT NULL,
  `context_type` varchar(191) NOT NULL,
  `context_id` bigint UNSIGNED NOT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversations_context_type_context_id_index` (`context_type`,`context_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `conversation_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `last_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversation_user_conversation_id_user_id_unique` (`conversation_id`,`user_id`),
  KEY `conversation_user_user_id_foreign` (`user_id`),
  CONSTRAINT `conversation_user_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_user_user_id_foreign`         FOREIGN KEY (`user_id`)         REFERENCES `users` (`id`)         ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `attachment_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign`       FOREIGN KEY (`sender_id`)       REFERENCES `users` (`id`)         ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Documents
-- =============================================================================

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(191) NOT NULL,
  `purpose` varchar(191) NOT NULL,
  `details` text DEFAULT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending_review','approved','rejected','ready_for_pickup','released','cancelled') NOT NULL DEFAULT 'pending_review',
  `pickup_date` date DEFAULT NULL,
  `pickup_schedule` varchar(191) DEFAULT NULL,
  `claim_requirements` text DEFAULT NULL,
  `processed_by_id` bigint UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_reference_no_unique` (`reference_no`),
  KEY `documents_user_id_foreign` (`user_id`),
  KEY `documents_status_index` (`status`),
  KEY `documents_processed_by_id_foreign` (`processed_by_id`),
  CONSTRAINT `documents_user_id_foreign`         FOREIGN KEY (`user_id`)         REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_processed_by_id_foreign` FOREIGN KEY (`processed_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Complaints & blotter
-- =============================================================================

CREATE TABLE `complaints` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(191) NOT NULL,
  `complainant_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `category` varchar(191) NOT NULL,
  `custom_category` varchar(191) DEFAULT NULL,
  `respondent_name` varchar(191) NOT NULL,
  `incident_date` date NOT NULL,
  `incident_location` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `witness_name` varchar(191) DEFAULT NULL,
  `witness_contact` varchar(20) DEFAULT NULL,
  `status` enum('pending_review','under_investigation','scheduled_for_mediation','scheduled_for_hearing','resolved','dismissed','escalated') NOT NULL DEFAULT 'pending_review',
  `assigned_officer_id` bigint UNSIGNED DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaints_reference_no_unique` (`reference_no`),
  KEY `complaints_complainant_id_foreign` (`complainant_id`),
  KEY `complaints_status_index` (`status`),
  KEY `complaints_assigned_officer_id_foreign` (`assigned_officer_id`),
  CONSTRAINT `complaints_complainant_id_foreign`     FOREIGN KEY (`complainant_id`)     REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_assigned_officer_id_foreign` FOREIGN KEY (`assigned_officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `complaint_evidence` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(191) NOT NULL,
  `original_name` varchar(191) NOT NULL,
  `mime_type` varchar(191) NOT NULL,
  `size_bytes` int UNSIGNED NOT NULL,
  `uploaded_by_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_evidence_complaint_id_foreign` (`complaint_id`),
  KEY `complaint_evidence_uploaded_by_id_foreign` (`uploaded_by_id`),
  CONSTRAINT `complaint_evidence_complaint_id_foreign`   FOREIGN KEY (`complaint_id`)   REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaint_evidence_uploaded_by_id_foreign` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`id`)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `blotter_records` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(191) NOT NULL,
  `complaint_id` bigint UNSIGNED DEFAULT NULL,
  `recorded_by_id` bigint UNSIGNED NOT NULL,
  `incident_type` varchar(191) NOT NULL,
  `narrative` text NOT NULL,
  `location` varchar(191) NOT NULL,
  `incident_at` datetime NOT NULL,
  `parties_involved` json DEFAULT NULL,
  `status` enum('open','investigating','closed','forwarded') NOT NULL DEFAULT 'open',
  `investigation_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blotter_records_reference_no_unique` (`reference_no`),
  KEY `blotter_records_complaint_id_foreign` (`complaint_id`),
  KEY `blotter_records_recorded_by_id_foreign` (`recorded_by_id`),
  KEY `blotter_records_status_index` (`status`),
  CONSTRAINT `blotter_records_complaint_id_foreign`   FOREIGN KEY (`complaint_id`)   REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blotter_records_recorded_by_id_foreign` FOREIGN KEY (`recorded_by_id`) REFERENCES `users` (`id`)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mediation_schedule` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint UNSIGNED NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `venue` varchar(191) NOT NULL,
  `assigned_officer_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('scheduled','completed','rescheduled','cancelled','no_show') NOT NULL DEFAULT 'scheduled',
  `outcome` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mediation_schedule_assigned_officer_id_foreign` (`assigned_officer_id`),
  KEY `mediation_schedule_complaint_id_scheduled_at_index` (`complaint_id`,`scheduled_at`),
  CONSTRAINT `mediation_schedule_complaint_id_foreign`        FOREIGN KEY (`complaint_id`)        REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mediation_schedule_assigned_officer_id_foreign` FOREIGN KEY (`assigned_officer_id`) REFERENCES `users` (`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hearing_schedule` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint UNSIGNED NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `venue` varchar(191) NOT NULL,
  `assigned_officer_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('scheduled','completed','rescheduled','cancelled','no_show') NOT NULL DEFAULT 'scheduled',
  `outcome` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hearing_schedule_assigned_officer_id_foreign` (`assigned_officer_id`),
  KEY `hearing_schedule_complaint_id_scheduled_at_index` (`complaint_id`,`scheduled_at`),
  CONSTRAINT `hearing_schedule_complaint_id_foreign`        FOREIGN KEY (`complaint_id`)        REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hearing_schedule_assigned_officer_id_foreign` FOREIGN KEY (`assigned_officer_id`) REFERENCES `users` (`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Appointments, announcements, notifications, audit
-- =============================================================================

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `purpose` varchar(191) NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `queue_number` varchar(191) DEFAULT NULL,
  `status` enum('requested','confirmed','served','cancelled','no_show') NOT NULL DEFAULT 'requested',
  `handled_by_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appointments_reference_no_unique` (`reference_no`),
  KEY `appointments_user_id_foreign` (`user_id`),
  KEY `appointments_status_index` (`status`),
  KEY `appointments_handled_by_id_foreign` (`handled_by_id`),
  KEY `appointments_scheduled_at_index` (`scheduled_at`),
  CONSTRAINT `appointments_user_id_foreign`       FOREIGN KEY (`user_id`)       REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_handled_by_id_foreign` FOREIGN KEY (`handled_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `announcements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `body` text NOT NULL,
  `cover_path` varchar(191) DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `announcements_slug_unique` (`slug`),
  KEY `announcements_is_published_index` (`is_published`),
  KEY `announcements_author_id_foreign` (`author_id`),
  CONSTRAINT `announcements_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `channel` varchar(191) NOT NULL DEFAULT 'database',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  KEY `notifications_notifiable_type_notifiable_id_read_at_index` (`notifiable_type`,`notifiable_id`,`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `module` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `activity_logs_module_index` (`module`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(191) NOT NULL,
  `auditable_type` varchar(191) NOT NULL,
  `auditable_id` bigint UNSIGNED NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audit_logs_auditable_type_auditable_id_event_index` (`auditable_type`,`auditable_id`,`event`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `login_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `result` enum('success','failed','locked') NOT NULL DEFAULT 'success',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_histories_user_id_foreign` (`user_id`),
  KEY `login_histories_result_index` (`result`),
  CONSTRAINT `login_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- SEED DATA
-- =============================================================================

-- Mark all migrations as already run so artisan won't re-execute them
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,  '2026_01_01_000001_create_users_table',                  1),
(2,  '2026_01_01_000002_create_cache_table',                  1),
(3,  '2026_01_01_000003_create_jobs_table',                   1),
(4,  '2026_01_01_000010_create_permission_tables',            1),
(5,  '2026_01_01_000020_create_addresses_tables',             1),
(6,  '2026_01_01_000030_create_households_table',             1),
(7,  '2026_01_01_000031_create_residents_table',              1),
(8,  '2026_01_01_000040_create_skill_services_table',         1),
(9,  '2026_01_01_000041_create_businesses_table',             1),
(10, '2026_01_01_000042_create_reviews_table',                1),
(11, '2026_01_01_000043_create_messages_table',               1),
(12, '2026_01_01_000050_create_documents_table',              1),
(13, '2026_01_01_000060_create_complaints_table',             1),
(14, '2026_01_01_000061_create_blotter_tables',               1),
(15, '2026_01_01_000070_create_appointments_table',           1),
(16, '2026_01_01_000080_create_announcements_table',          1),
(17, '2026_01_01_000090_create_notifications_table',          1),
(18, '2026_01_01_000091_create_activity_audit_login_tables',  1);

-- Permissions (24)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1,  'manage_users',           'web', NOW(), NOW()),
(2,  'manage_roles',            'web', NOW(), NOW()),
(3,  'manage_settings',         'web', NOW(), NOW()),
(4,  'approve_residents',       'web', NOW(), NOW()),
(5,  'verify_residents',        'web', NOW(), NOW()),
(6,  'manage_documents',        'web', NOW(), NOW()),
(7,  'process_documents',       'web', NOW(), NOW()),
(8,  'release_documents',       'web', NOW(), NOW()),
(9,  'manage_complaints',       'web', NOW(), NOW()),
(10, 'investigate_complaints',  'web', NOW(), NOW()),
(11, 'mediate_complaints',      'web', NOW(), NOW()),
(12, 'resolve_complaints',      'web', NOW(), NOW()),
(13, 'manage_blotter',          'web', NOW(), NOW()),
(14, 'create_blotter',          'web', NOW(), NOW()),
(15, 'manage_skills_services',  'web', NOW(), NOW()),
(16, 'approve_skills_services', 'web', NOW(), NOW()),
(17, 'manage_businesses',       'web', NOW(), NOW()),
(18, 'approve_businesses',      'web', NOW(), NOW()),
(19, 'inspect_businesses',      'web', NOW(), NOW()),
(20, 'view_analytics',          'web', NOW(), NOW()),
(21, 'export_reports',          'web', NOW(), NOW()),
(22, 'manage_announcements',    'web', NOW(), NOW()),
(23, 'view_activity_logs',      'web', NOW(), NOW()),
(24, 'view_audit_logs',         'web', NOW(), NOW());

-- Roles (6)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin',     'web', NOW(), NOW()),
(2, 'captain',   'web', NOW(), NOW()),
(3, 'kagawad',   'web', NOW(), NOW()),
(4, 'secretary', 'web', NOW(), NOW()),
(5, 'tanod',     'web', NOW(), NOW()),
(6, 'resident',  'web', NOW(), NOW());

-- admin → ALL permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),
(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),
(21,1),(22,1),(23,1),(24,1);

-- captain
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(4,2),(16,2),(18,2),(9,2),(12,2),(20,2),(21,2),(22,2),(23,2),(24,2);

-- kagawad
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(4,3),(16,3),(18,3),(20,3),(21,3),(22,3);

-- secretary
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(5,4),(16,4),(18,4),(7,4),(8,4),(6,4),(11,4),(22,4);

-- tanod
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(13,5),(14,5),(10,5),(11,5);

-- Geography (Pampanga → San Fernando → Panipuan → 7 puroks)
INSERT INTO `provinces` (`id`, `name`, `region`, `created_at`, `updated_at`) VALUES
(1, 'Pampanga', 'Region III (Central Luzon)', NOW(), NOW());

INSERT INTO `cities` (`id`, `province_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'City of San Fernando', NOW(), NOW());

INSERT INTO `barangays` (`id`, `city_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Panipuan', NOW(), NOW());

INSERT INTO `puroks` (`id`, `barangay_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Purok 1', NOW(), NOW()),
(2, 1, 'Purok 2', NOW(), NOW()),
(3, 1, 'Purok 3', NOW(), NOW()),
(4, 1, 'Purok 4', NOW(), NOW()),
(5, 1, 'Purok 5', NOW(), NOW()),
(6, 1, 'Purok 6', NOW(), NOW()),
(7, 1, 'Purok 7', NOW(), NOW());

-- Staff accounts (password = "password" — CHANGE AFTER FIRST LOGIN)
-- Hash is the documented Laravel default bcrypt hash for the string "password".
INSERT INTO `users`
  (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `phone`, `password`,
   `account_status`, `approved_by_secretary`, `approved_by_kagawad`, `approved_by_captain`,
   `approved_by_secretary_at`, `approved_by_kagawad_at`, `approved_by_captain_at`,
   `created_at`, `updated_at`)
VALUES
(1, 'System',  'Administrator', 'admin@panipuan.gov.ph',     NOW(), '09170000001',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'approved', 1, 1, 1, NOW(), NOW(), NOW(), NOW(), NOW()),
(2, 'Juan',    'Dela Cruz',     'captain@panipuan.gov.ph',   NOW(), '09170000002',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'approved', 1, 1, 1, NOW(), NOW(), NOW(), NOW(), NOW()),
(3, 'Maria',   'Santos',        'kagawad@panipuan.gov.ph',   NOW(), '09170000003',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'approved', 1, 1, 1, NOW(), NOW(), NOW(), NOW(), NOW()),
(4, 'Ana',     'Reyes',         'secretary@panipuan.gov.ph', NOW(), '09170000004',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'approved', 1, 1, 1, NOW(), NOW(), NOW(), NOW(), NOW()),
(5, 'Pedro',   'Garcia',        'tanod@panipuan.gov.ph',     NOW(), '09170000005',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'approved', 1, 1, 1, NOW(), NOW(), NOW(), NOW(), NOW());

-- Role assignments
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1), -- admin
(2, 'App\\Models\\User', 2), -- captain
(3, 'App\\Models\\User', 3), -- kagawad
(4, 'App\\Models\\User', 4), -- secretary
(5, 'App\\Models\\User', 5); -- tanod

-- Announcements (authored by captain = user id 2)
INSERT INTO `announcements`
  (`id`, `title`, `slug`, `body`, `priority`, `is_published`, `published_at`,
   `author_id`, `created_at`, `updated_at`)
VALUES
(1, 'Welcome to PanipOne — Your Digital Barangay',
    'welcome-to-panipone-your-digital-barangay',
    'PanipOne is the official online system of Barangay Panipuan for document requests, complaints, and community services. Register today to enjoy the convenience of online transactions.',
    'high',   1, NOW(), 2, NOW(), NOW()),
(2, 'Monthly Clean-up Drive — Every First Saturday',
    'monthly-clean-up-drive-every-first-saturday',
    'Join your fellow residents every first Saturday of the month for our community clean-up drive. Assembly area: Barangay Hall, 6:00 AM.',
    'normal', 1, NOW(), 2, NOW(), NOW()),
(3, 'Emergency Hotlines',
    'emergency-hotlines',
    'Save these hotlines: Brgy. Hotline (045) 000-0000, Tanod 0917-000-0000, City Disaster Office 0917-111-1111.',
    'urgent', 1, NOW(), 2, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- DONE. Open the app at http://localhost:8000 (php artisan serve) and log in
-- with any staff account above using the password:  password
-- Then change every default password immediately.
-- =============================================================================
