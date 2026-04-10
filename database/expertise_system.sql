-- ============================================================================
-- EXPERTISE CATEGORIES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS expertise_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- USER EXPERTISE JOIN TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS user_expertise (
    expertise_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'intermediate',
    years_experience INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES expertise_categories(category_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_category (user_id, category_id),
    INDEX idx_user_id (user_id),
    INDEX idx_category_id (category_id)
);

-- ============================================================================
-- INSERT PREDEFINED EXPERTISE CATEGORIES
-- ============================================================================
INSERT IGNORE INTO expertise_categories (category_name, description) VALUES
('Software Development', 'Full Stack, Backend, Frontend, Mobile'),
('Cloud & DevOps', 'AWS, Azure, GCP, CI/CD, Docker, Kubernetes'),
('Cybersecurity', 'Ethical Hacking, Network Security, SOC'),
('Data & AI/ML', 'Data Science, Machine Learning, LLMs'),
('UI/UX & Product', 'UX Research, Product Management, Design Systems'),
('Networking & Infra', 'Network Engineering, Sysadmin, Linux'),
('Database Systems', 'SQL, NoSQL, System Design, Microservices'),
('Embedded & IoT', 'Arduino, FPGA, Firmware, Hardware Design'),
('QA & Testing', 'Test Automation, Performance Testing, QA'),
('Computer Architecture', 'CPU Design, MIPS, Compilers, OS Internals'),
('Open Source & Tools', 'Git, Linux CLI, Contributing to OSS'),
('Tech Career & Interview Prep', 'Resume Review, DSA, Placement Guidance');

-- ============================================================================
-- OPTIONAL: Add expertise_areas_json column to alumni_profiles for backward compatibility
-- ============================================================================
-- ALTER TABLE alumni_profiles ADD COLUMN expertise_areas_json JSON NULL;
