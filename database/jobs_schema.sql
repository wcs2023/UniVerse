-- SQL Schema for Jobs Table
-- Run this in phpMyAdmin to create the jobs table in your my_db database

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_id` INT(11) NOT NULL,
  `job_title` VARCHAR(255) NOT NULL,
  `job_type` ENUM('full-time', 'part-time', 'internship', 'contract') NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `salary` VARCHAR(100) DEFAULT NULL,
  `deadline` DATE NOT NULL,
  `description` TEXT NOT NULL,
  `requirements` TEXT NOT NULL,
  `responsibilities` TEXT DEFAULT NULL,
  `status` ENUM('active', 'closed', 'draft') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `status` (`status`),
  KEY `deadline` (`deadline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Create a companies table if you don't have one yet
CREATE TABLE IF NOT EXISTS `companies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Add a foreign key constraint (uncomment if you want strict referential integrity)
-- ALTER TABLE `jobs`
--   ADD CONSTRAINT `fk_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Insert some sample data for testing (optional)
-- Make sure to create a company first or use an existing company_id
-- INSERT INTO `jobs` (`company_id`, `job_title`, `job_type`, `location`, `salary`, `deadline`, `description`, `requirements`, `status`) 
-- VALUES 
-- (1, 'Senior Software Engineer', 'full-time', 'Colombo, Sri Lanka', 'LKR 150,000 - 200,000', '2025-12-31', 'We are looking for an experienced software engineer...', 'Bachelor\'s degree in Computer Science\n5+ years of experience\nProficient in PHP, MySQL', 'active'),
-- (1, 'UX Designer', 'full-time', 'Remote', 'LKR 100,000 - 150,000', '2025-11-30', 'Join our creative team as a UX Designer...', 'Portfolio required\n3+ years of experience\nFigma, Adobe XD proficiency', 'active');
