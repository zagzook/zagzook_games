SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ==============================================
-- SECTION 1: CORE USER MANAGEMENT (Public Frontend)
-- ==============================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_level` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin, 2=Pro Paid, 3=Free Registered',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `user_level` (`user_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- SECTION 2: STANDARD TRONGATE ADMIN TABLES
-- The secure backend infrastructure.
-- ==============================================
CREATE TABLE `trongate_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) DEFAULT NULL,
  `user_level_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `trongate_administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `trongate_user_id` int(11) DEFAULT NULL COMMENT 'Links to main users table id',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `failed_login_attempts` int(11) DEFAULT 0,
  `last_failed_attempt` int(11) DEFAULT 0,
  `login_blocked_until` int(11) DEFAULT 0,
  `failed_login_ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `trongate_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(125) DEFAULT NULL,
  `user_id` int(11) DEFAULT 0,
  `expiry_date` int(11) DEFAULT NULL,
  `code` varchar(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `trongate_user_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_title` varchar(125) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ==============================================
-- SECTION 3: INITIAL SEED DATA (Admin Account)
-- ==============================================
-- Default Super Admin Pass: 'password' (CHANGE IMMEDIATELY)

-- 1. Create backend level info
INSERT INTO `trongate_user_levels` (`id`, `level_title`) VALUES (1, 'admin');

-- 2. Create backend admin identities
INSERT INTO `trongate_users` (`id`, `code`, `user_level_id`) VALUES (1, NULL, 1);
INSERT INTO `trongate_administrators` (`id`, `username`, `password`, `trongate_user_id`, `active`, `failed_login_attempts`, `last_failed_attempt`, `login_blocked_until`, `failed_login_ip`) VALUES
(1, 'admin', '$2y$11$SoHZDvbfLSRHAi3WiKIBiu.tAoi/GCBBO4HRxVX1I3qQkq3wCWfXi', 1, 1, 0, 0, 0, '');

-- 3. Create corresponding frontend public Identity (Level 1)
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `user_level`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$11$gwKqY.o8mK3R6V3jWzN5ceU.vF9tQY6e7zXzX6.s5.u1.u1.u1.u1', 1, NOW());


-- ==============================================
-- SECTION 4: GAME DEFINITIONS (The Menu)
-- ==============================================
CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL COMMENT 'URL friendly unique name',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `logic_engine` varchar(255) NOT NULL COMMENT 'Which JS file handles this game type',
  `settings` json DEFAULT NULL COMMENT 'Specific configuration for this variant',
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `sort_order` (`sort_order`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert your example game
INSERT INTO `games` (`id`, `name`, `slug`, `sort_order`, `logic_engine`, `settings`, `is_premium`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Classic Sudoku', 'standard9x9', 1, 'sudoku', '{\"variant\": \"standard\", \"grid_size\": 9, \"symbol_type\": \"numbers\", \"shaded_cells\": [], \"mistakes_allowed\": {\"easy\": 3, \"hard\": 5, \"medium\": 4}}', 0, 1, NOW(), NOW());


-- ==============================================
-- SECTION 5: GAME GENERATION DNA
-- ==============================================
CREATE TABLE `game_solutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(55) NOT NULL,
  `data_string` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `game_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grid_size` varchar(20) NOT NULL,
  `difficulty` varchar(20) NOT NULL,
  `game_type` varchar(55) NOT NULL,
  `data_string` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `difficulty` (`difficulty`),
  KEY `game_type` (`game_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `game_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grid_size` varchar(20) NOT NULL,
  `data_string` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ==============================================
-- SECTION 6: GAMEPLAY DATA
-- ==============================================
CREATE TABLE `game_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '0 for guests, otherwise users.id',
  `game_slug` varchar(55) NOT NULL,
  `puzzle_composite_id` varchar(100) NOT NULL,
  `current_state_json` longtext NOT NULL,
  `metadata_json` json DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `updated_at` (`updated_at`)
  -- NOTE: No Foreign Key on user_id here, because Guest ID 0 does not exist in users table.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `game_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Links to users.id (Only registered users save scores)',
  `game_slug` varchar(55) NOT NULL,
  `score` bigint(20) NOT NULL,
  `game_data_json` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `leaderboard_idx` (`game_slug`,`score` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ==============================================
-- SECTION 7: FOREIGN KEYS
-- ==============================================
-- Link scores to users (Only for registered users)
ALTER TABLE `game_scores`
  ADD CONSTRAINT `fk_score_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;