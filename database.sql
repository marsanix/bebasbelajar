-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.40 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping structure for table bebasbelajar.bboc_users
CREATE TABLE IF NOT EXISTS `bboc_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','member') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'member',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_users: ~2 rows (approximately)
INSERT INTO `bboc_users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Marsani', 'marsanix@gmail.com', '$2y$10$OnpUYtGZSyBZ0PAIZLK/AOgTWCZ8pSVI3F1SQNP2aqUf0LSW8kbPe', 'admin', 1, NULL, NULL, NULL),
	(3, 'Member123', 'member@member.com', '$2y$10$Q9eMdulB87p1VFa1xBc72eLyXFSKkBqxUKzyGurCw.82g9W5ISV7u', 'member', 1, NULL, NULL, NULL),
	(4, 'Member007', 'member007@member.com', '$2y$10$6IWBk8OFfBquTv74Q1.8c.VrCTynmWZZBn8CAh0O7nCyQoXWBnXbu', 'member', 1, NULL, NULL, NULL);


-- Dumping structure for table bebasbelajar.bboc_categories
CREATE TABLE IF NOT EXISTS `bboc_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_categories: ~4 rows (approximately)
INSERT INTO `bboc_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Cybersecurity', NULL, NULL),
	(2, 'Web Development', NULL, NULL),
	(3, 'Mobile Development', NULL, NULL),
	(4, 'Designer', NULL, NULL);



-- Dumping structure for table bebasbelajar.bboc_courses
CREATE TABLE IF NOT EXISTS `bboc_courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `thumbnail` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_approved` tinyint NOT NULL DEFAULT '0',
  `is_published` tinyint NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bboc_courses_user_id_foreign` (`user_id`),
  CONSTRAINT `bboc_courses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `bboc_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_courses: ~5 rows (approximately)
INSERT INTO `bboc_courses` (`id`, `category_id`, `user_id`, `title`, `description`, `thumbnail`, `is_approved`, `is_published`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, 'Computer Network & Security', 'Learning how to make a network infrastructure for industry standards with security compliance', '1753298320_668116d4a126661b4790.jpg', 1, 1, 1, NULL, NULL, NULL),
	(2, NULL, 1, 'Android Kotlin Development', 'Program pelatihan dan pembelajaran pengembangan aplikasi mobile Android Native Kotlin', '1753366213_a61a42dacfb48e76dec0.png', 1, 1, 1, NULL, NULL, '2025-07-24 14:11:38'),
	(3, 3, 1, 'Android Kotlin Development', 'Program pelatihan dan pembelajaran pengembangan aplikasi mobile Android Native Kotlin', '1753366317_e9b0c48b2780ff755ff4.png', 1, 1, 1, NULL, NULL, NULL),
	(4, 2, 1, 'Front-End Development - React', 'Pelatihan pengembangan web aplikasi menggunakan React JS', '1753437624_5ef704bfabbb12bf8a64.png', 1, 1, 1, NULL, NULL, NULL),
	(7, 3, 3, 'UI/UX Design for Mobile Apps', 'xcvbcbcvb zxcvbcvb zxvcbxcvb xzvcbcxvbcxvbcxvbn', '1753504170_9e2a9e3422438329f899.png', 0, 0, 3, NULL, NULL, NULL);

-- Dumping structure for table bebasbelajar.bboc_course_materials
CREATE TABLE IF NOT EXISTS `bboc_course_materials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `title` text COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('article','video','youtube','audio','image','ebook') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_general_ci,
  `youtube_link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_path` text COLLATE utf8mb4_general_ci,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bboc_course_materials_course_id_foreign` (`course_id`),
  CONSTRAINT `bboc_course_materials_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `bboc_courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_course_materials: ~6 rows (approximately)
INSERT INTO `bboc_course_materials` (`id`, `course_id`, `title`, `type`, `content`, `youtube_link`, `file_path`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'Introduction of Cybersecurity', 'article', 'Cybersecurity is about taking action to secure data, the network, and devices', NULL, '1753300385_51cc5dd10cf5921744ba', 0, NULL, NULL, NULL),
	(2, 1, 'Computer & Networking', 'article', 'Di materi kali ini kita membahas tentang komputer dan jaringan', NULL, '1753330688_515f4d203bc6dbef8329', 0, NULL, NULL, NULL),
	(3, 1, 'Basic Networking', 'image', 'Pembahasan mengenai Dasar-dasar Jaringan komputer dan Internet', NULL, 'images/1753330782_d2e1876028faad7bb64a.jpg', 0, NULL, NULL, NULL),
	(5, 1, 'Cyber Security Fundamental', 'youtube', 'video penjelasan tentang dasar-dasar keamanan siber', 'https://youtu.be/wyDYB626qiw?si=R1gBXPfE7gGDV8Wx', NULL, 0, NULL, NULL, NULL),
	(6, 3, 'Introduction of Mobile Application', 'article', 'Pengenalan Aplikasi Mobile', NULL, NULL, 0, NULL, NULL, NULL),
	(7, 4, 'Introduction of Website', 'article', 'Pengenalan dengan teknologi web dan website', NULL, NULL, 0, NULL, NULL, NULL);

-- Dumping structure for table bebasbelajar.bboc_course_users
CREATE TABLE IF NOT EXISTS `bboc_course_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `user_id` int NOT NULL,
  `is_enrolled` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bboc_course_users_course_id_foreign` (`course_id`),
  KEY `bboc_course_users_user_id_foreign` (`user_id`),
  CONSTRAINT `bboc_course_users_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `bboc_courses` (`id`),
  CONSTRAINT `bboc_course_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `bboc_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_course_users: ~3 rows (approximately)
INSERT INTO `bboc_course_users` (`id`, `course_id`, `user_id`, `is_enrolled`, `created_at`, `updated_at`) VALUES
	(1, 3, 1, 1, NULL, NULL),
	(2, 1, 1, 1, NULL, NULL),
	(3, 1, 3, 1, NULL, NULL),
	(4, 1, 4, 1, NULL, NULL);

-- Dumping structure for table bebasbelajar.bboc_migrations
CREATE TABLE IF NOT EXISTS `bboc_migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_migrations: ~8 rows (approximately)
INSERT INTO `bboc_migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
	(1, '2025-07-23-131830', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1753277668, 1),
	(2, '2025-07-23-131844', 'App\\Database\\Migrations\\CreateCoursesTable', 'default', 'App', 1753277668, 1),
	(3, '2025-07-23-131854', 'App\\Database\\Migrations\\CreateCourseMaterialsTable', 'default', 'App', 1753277668, 1),
	(4, '2025-07-23-131901', 'App\\Database\\Migrations\\CreateCourseUsersTable', 'default', 'App', 1753277668, 1),
	(5, '2025-07-23-131909', 'App\\Database\\Migrations\\CreateCommentsTable', 'default', 'App', 1753277668, 1),
	(6, '2025-07-24-043609', 'App\\Database\\Migrations\\AddYoutubeLinkToCourseMaterials', 'default', 'App', 1753331831, 2),
	(7, '2025-07-24-131037', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1753363033, 3),
	(8, '2025-07-24-131114', 'App\\Database\\Migrations\\AddCategoryIdToCourses', 'default', 'App', 1753363033, 3);

-- Dumping structure for table bebasbelajar.bboc_comments
CREATE TABLE IF NOT EXISTS `bboc_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_material_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT (now()),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bboc_comments_user_id_foreign` (`user_id`),
  KEY `bboc_comments_course_material_id_foreign` (`course_material_id`),
  CONSTRAINT `bboc_comments_course_material_id_foreign` FOREIGN KEY (`course_material_id`) REFERENCES `bboc_course_materials` (`id`),
  CONSTRAINT `bboc_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `bboc_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bebasbelajar.bboc_comments: ~6 rows (approximately)
INSERT INTO `bboc_comments` (`id`, `user_id`, `course_material_id`, `comment`, `parent_id`, `created_at`, `deleted_at`) VALUES
	(1, 3, 5, 'testing', NULL, NULL, NULL),
	(2, 1, 5, 'ok mantap', NULL, NULL, NULL),
	(3, 1, 5, 'sdfasdfg', 1, NULL, NULL),
	(4, 1, 5, 'test', 2, NULL, NULL),
	(5, 1, 5, 'zxcvxczvb', 2, NULL, NULL),
	(7, 1, 5, 'yaps betul', 3, NULL, NULL),
	(8, 1, 5, 'sdafsadfgsdg', 3, '2025-07-26 02:11:48', NULL),
	(9, 4, 5, 'testing', 3, '2025-07-26 11:10:44', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
