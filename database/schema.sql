-- Jolis SMS Database Schema
-- 3NF Normalized MySQL Schema for Uganda Secondary School Management

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

-- --------------------------------------------------------
-- Table: academic_years
-- --------------------------------------------------------
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `is_current` TINYINT(1) DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: terms
-- --------------------------------------------------------
DROP TABLE IF EXISTS `terms`;
CREATE TABLE `terms` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `academic_year_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `term_number` TINYINT NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `is_current` TINYINT(1) DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: levels (O'Level, A'Level)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: classes (S1-S6)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `level_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `order_index` TINYINT NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`),
    FOREIGN KEY (`level_id`) REFERENCES `levels`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: streams (A, B, C, D)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `streams`;
CREATE TABLE `streams` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(10) NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: class_streams (junction table)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `class_streams`;
CREATE TABLE `class_streams` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `class_id` INT UNSIGNED NOT NULL,
    `stream_id` INT UNSIGNED NOT NULL,
    `academic_year_id` INT UNSIGNED NOT NULL,
    `capacity` INT DEFAULT 50,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `class_stream_year` (`class_id`, `stream_id`, `academic_year_id`),
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`stream_id`) REFERENCES `streams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: subject_categories
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subject_categories`;
CREATE TABLE `subject_categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: subjects
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT UNSIGNED,
    `level_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `paper_count` TINYINT DEFAULT 1,
    `is_compulsory` TINYINT(1) DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code_level` (`code`, `level_id`),
    FOREIGN KEY (`category_id`) REFERENCES `subject_categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`level_id`) REFERENCES `levels`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: subject_papers
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subject_papers`;
CREATE TABLE `subject_papers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `subject_id` INT UNSIGNED NOT NULL,
    `paper_number` TINYINT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `max_marks` INT DEFAULT 100,
    `weight_percentage` DECIMAL(5,2) DEFAULT 100.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `subject_paper` (`subject_id`, `paper_number`),
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('instructor', 'student') NOT NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20),
    `avatar` VARCHAR(255),
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `email_verified_at` TIMESTAMP NULL,
    `remember_token` VARCHAR(100),
    `last_login_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: instructors
-- --------------------------------------------------------
DROP TABLE IF EXISTS `instructors`;
CREATE TABLE `instructors` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `employee_id` VARCHAR(50),
    `qualification` VARCHAR(255),
    `specialization` VARCHAR(255),
    `date_joined` DATE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    UNIQUE KEY `employee_id` (`employee_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: students
-- --------------------------------------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `student_number` VARCHAR(50) NOT NULL,
    `admission_date` DATE,
    `date_of_birth` DATE,
    `gender` ENUM('male', 'female') NOT NULL,
    `address` TEXT,
    `guardian_name` VARCHAR(200),
    `guardian_phone` VARCHAR(20),
    `guardian_email` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    UNIQUE KEY `student_number` (`student_number`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: student_enrollments
-- --------------------------------------------------------
DROP TABLE IF EXISTS `student_enrollments`;
CREATE TABLE `student_enrollments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT UNSIGNED NOT NULL,
    `class_stream_id` INT UNSIGNED NOT NULL,
    `academic_year_id` INT UNSIGNED NOT NULL,
    `term_id` INT UNSIGNED NOT NULL,
    `enrollment_date` DATE NOT NULL,
    `status` ENUM('active', 'transferred', 'graduated', 'dropped') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `student_year_term` (`student_id`, `academic_year_id`, `term_id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_stream_id`) REFERENCES `class_streams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`term_id`) REFERENCES `terms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: instructor_subjects (which instructor teaches which subject)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `instructor_subjects`;
CREATE TABLE `instructor_subjects` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `instructor_id` INT UNSIGNED NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `class_stream_id` INT UNSIGNED NOT NULL,
    `academic_year_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `instructor_subject_class` (`instructor_id`, `subject_id`, `class_stream_id`, `academic_year_id`),
    FOREIGN KEY (`instructor_id`) REFERENCES `instructors`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_stream_id`) REFERENCES `class_streams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: student_subjects (which subjects a student takes)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `student_subjects`;
CREATE TABLE `student_subjects` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT UNSIGNED NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `academic_year_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `student_subject_year` (`student_id`, `subject_id`, `academic_year_id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: exam_types (BOT, MID, EOT, UCE, UACE)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `exam_types`;
CREATE TABLE `exam_types` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `weight_percentage` DECIMAL(5,2) NOT NULL,
    `is_national` TINYINT(1) DEFAULT 0,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: exams
-- --------------------------------------------------------
DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `exam_type_id` INT UNSIGNED NOT NULL,
    `term_id` INT UNSIGNED NOT NULL,
    `class_stream_id` INT UNSIGNED NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `paper_id` INT UNSIGNED,
    `name` VARCHAR(200) NOT NULL,
    `max_marks` INT DEFAULT 100,
    `exam_date` DATE,
    `status` ENUM('scheduled', 'ongoing', 'completed', 'cancelled') DEFAULT 'scheduled',
    `created_by` INT UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`exam_type_id`) REFERENCES `exam_types`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`term_id`) REFERENCES `terms`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_stream_id`) REFERENCES `class_streams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`paper_id`) REFERENCES `subject_papers`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: exam_results
-- --------------------------------------------------------
DROP TABLE IF EXISTS `exam_results`;
CREATE TABLE `exam_results` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `exam_id` INT UNSIGNED NOT NULL,
    `student_id` INT UNSIGNED NOT NULL,
    `marks_obtained` DECIMAL(5,2),
    `grade` VARCHAR(5),
    `points` TINYINT,
    `comment` TEXT,
    `entered_by` INT UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `exam_student` (`exam_id`, `student_id`),
    FOREIGN KEY (`exam_id`) REFERENCES `exams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`entered_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: term_results (aggregated term results)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `term_results`;
CREATE TABLE `term_results` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT UNSIGNED NOT NULL,
    `term_id` INT UNSIGNED NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `bot_marks` DECIMAL(5,2),
    `mid_marks` DECIMAL(5,2),
    `eot_marks` DECIMAL(5,2),
    `final_marks` DECIMAL(5,2),
    `grade` VARCHAR(5),
    `points` TINYINT,
    `comment` TEXT,
    `position` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `student_term_subject` (`student_id`, `term_id`, `subject_id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`term_id`) REFERENCES `terms`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: national_exam_results (UCE/UACE results from UNEB)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `national_exam_results`;
CREATE TABLE `national_exam_results` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT UNSIGNED NOT NULL,
    `exam_type` ENUM('UCE', 'UACE') NOT NULL,
    `exam_year` YEAR NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `grade` VARCHAR(5) NOT NULL,
    `points` TINYINT,
    `aggregate` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `student_exam_subject` (`student_id`, `exam_type`, `exam_year`, `subject_id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: homework
-- --------------------------------------------------------
DROP TABLE IF EXISTS `homework`;
CREATE TABLE `homework` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `instructor_id` INT UNSIGNED NOT NULL,
    `class_stream_id` INT UNSIGNED NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `attachment` VARCHAR(255),
    `due_date` DATETIME NOT NULL,
    `max_marks` INT DEFAULT 100,
    `status` ENUM('active', 'closed', 'cancelled') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instructor_id`) REFERENCES `instructors`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_stream_id`) REFERENCES `class_streams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: homework_submissions
-- --------------------------------------------------------
DROP TABLE IF EXISTS `homework_submissions`;
CREATE TABLE `homework_submissions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `homework_id` INT UNSIGNED NOT NULL,
    `student_id` INT UNSIGNED NOT NULL,
    `submission_text` TEXT,
    `attachment` VARCHAR(255),
    `marks_obtained` DECIMAL(5,2),
    `feedback` TEXT,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `graded_at` TIMESTAMP NULL,
    `graded_by` INT UNSIGNED,
    `status` ENUM('submitted', 'graded', 'returned') DEFAULT 'submitted',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `homework_student` (`homework_id`, `student_id`),
    FOREIGN KEY (`homework_id`) REFERENCES `homework`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`graded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: exam_scripts (uploaded exam papers/scripts)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `exam_scripts`;
CREATE TABLE `exam_scripts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `exam_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `file_path` VARCHAR(255) NOT NULL,
    `file_type` VARCHAR(50),
    `file_size` INT,
    `uploaded_by` INT UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`exam_id`) REFERENCES `exams`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: notices
-- --------------------------------------------------------
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `target_role` ENUM('all', 'instructor', 'student'),
    `target_class_id` INT UNSIGNED,
    `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
    `is_pinned` TINYINT(1) DEFAULT 0,
    `published_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `created_by` INT UNSIGNED,
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`target_class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_resets
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `email` (`email`),
    KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: activity_logs
-- --------------------------------------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED,
    `action` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL,
    `value` TEXT,
    `type` VARCHAR(50) DEFAULT 'string',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: grading_scales (O'Level grading)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `grading_scales`;
CREATE TABLE `grading_scales` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `level_id` INT UNSIGNED NOT NULL,
    `grade` VARCHAR(5) NOT NULL,
    `min_marks` DECIMAL(5,2) NOT NULL,
    `max_marks` DECIMAL(5,2) NOT NULL,
    `points` TINYINT NOT NULL,
    `comment` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`level_id`) REFERENCES `levels`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
